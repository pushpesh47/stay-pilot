<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\OfflineBooking;
use App\Models\OfflineBookingExtension;
use App\Models\OfflineBookingGuest;
use App\Models\OfflineBookingPayment;
use App\Models\Branch;
use App\Models\PropertyImage;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Models\Admin;
use App\Models\City;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Exports\OfflineBookingListExport;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\File;

class AdminDashboardController extends Controller
{
    public function adminDashboard(Request $request)
    {
        // Dashboard Title
        $data['title'] = 'Dashboard';

        if (Auth::user()->isSuperAdmin()) {
            $data['branches'] = Branch::with(['city'])
                ->where('status', 'active')
                ->get();
        } else {
            $data['branches'] = Branch::with(['city'])
                ->where('id', Auth::user()->branch_id)
                ->where('status', 'active')
                ->get();
        }

        $data['branchId'] = $branchId =  $request->branch_id;
        $data['branchProperties'] = Property::where('branch_id', $branchId)->get();
        $data['propertyId'] = $propertyId = $request->property_id;

        // Base Queries
        $propertyQuery = Property::query();
        $bookingQuery = OfflineBooking::query();
        $paymentQuery = OfflineBookingPayment::query();
        $expenseQuery = Expense::query();

        $monthStart = now()->startOfMonth()->startOfDay();
        $monthEnd = now()->endOfDay();

        // Apply Branch Restriction
        $this->applyBranchRestriction($propertyQuery, $bookingQuery, $expenseQuery, $paymentQuery);

        // Apply Global Dashboard Filters
        $this->applyDashboardFilters($propertyQuery, $bookingQuery, $expenseQuery, $paymentQuery, $branchId, $propertyId);

        $activeBookingQuery = (clone $bookingQuery)->where('offline_bookings.status', 'active');

        $businessBookingQuery = (clone $bookingQuery)->whereIn('offline_bookings.status', ['active', 'completed']);

        // Load Dashboard Sections
        $this->loadLiveOperations($data, $propertyQuery, $businessBookingQuery);
        $this->loadUpcomingOperations($data, $activeBookingQuery, $propertyQuery);
        $this->loadTodayBusinessStats($data, $propertyQuery, $businessBookingQuery, $expenseQuery, $paymentQuery);
        $this->loadMonthlyStats($data, $propertyQuery, $businessBookingQuery, $expenseQuery, $paymentQuery);
        $this->loadOccupancyRevenueCharts($data, $propertyQuery, $businessBookingQuery, $paymentQuery);
        $this->prepareTopRevenueData($data, $businessBookingQuery, $monthStart, $monthEnd);
        $this->loadOperationsCenter($data, $bookingQuery);

        return view('admin.dashboard.dashboard', $data);
    }

    /*
    | Apply Branch Restriction
    */
    private function applyBranchRestriction(&$propertyQuery, &$bookingQuery, &$expenseQuery, &$paymentQuery) {
        if (!Auth::user()->isSuperAdmin()) {

            $branchId = Auth::user()->branch_id;

            $propertyQuery->where('branch_id', $branchId);

            $bookingQuery->whereHas('property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $expenseQuery->whereHas('property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $paymentQuery->whereHas('booking.property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
            
        }
    }

    /*
    | Global Dashboard Filters
    */
    private function applyDashboardFilters(&$propertyQuery, &$bookingQuery, &$expenseQuery, &$paymentQuery, $branchId, $propertyId) {
        if (!empty($branchId)) {

            $propertyQuery->where('branch_id', $branchId);

            $bookingQuery->whereHas('property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $expenseQuery->whereHas('property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $paymentQuery->whereHas('booking.property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

        }

        if (!empty($propertyId)) {

            $propertyQuery->where('id', $propertyId);

            $bookingQuery->where('property_id', $propertyId);

            $expenseQuery->where('property_id', $propertyId);

            $paymentQuery->whereHas('booking', function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            });

        }

    }

    // Section 1 : Live Operations
    private function loadLiveOperations(&$data, $propertyQuery, $bookingQuery)
    {
        $occupiedPropertyIds = (clone $bookingQuery)
            ->where('status', 'active')
            ->where('check_in', '<=', now())
            ->where('check_out', '>', now())
            ->pluck('property_id');

        $data['availableProperties'] = (clone $propertyQuery)
            ->where('status', 'active')
            ->whereNotIn('id', $occupiedPropertyIds)
            ->count();

        $data['occupiedProperties'] = $occupiedPropertyIds->unique()->count();

        $data['reservedProperties'] = (clone $bookingQuery)
            ->where('status', 'active')
            ->where('check_in', '>', now())
            ->distinct('property_id')
            ->count('property_id');

        $data['inHouseGuests'] = (clone $bookingQuery)
            ->where('status', 'active')
            ->where('check_in', '<=', now())
            ->where('check_out', '>', now())
            ->sum('total_guests');

        $data['todayCheckIns'] = (clone $bookingQuery)
            ->where('status', 'active')
            ->whereDate('check_in', today())
            ->count();

        $data['todayCheckOuts'] = (clone $bookingQuery)
            ->where('status', 'active')
            ->whereDate('check_out', today())
            ->count();

        // Includes Active + Completed bookings
        $data['pendingPayment'] = (clone $bookingQuery)
            ->selectRaw('COALESCE(SUM(total_amount - paid_amount),0) as pending')
            ->value('pending');

        
        $data['outOfServiceProperties'] = (clone $propertyQuery)
            ->where('status', 'inactive')
            ->count();;
    }

    //Upcoming Operations
    private function loadUpcomingOperations(&$data, $bookingQuery, $propertyQuery)
    {
        $today = Carbon::today();

        $tomorrowStart = $today->copy()->addDay()->startOfDay();
        $tomorrowEnd = $today->copy()->addDay()->endOfDay();

        $activeBookingQuery = clone $bookingQuery;

        //Tomorrow's Check-ins
        $data['tomorrowCheckIns'] = (clone $activeBookingQuery)
            ->whereBetween('check_in', [$tomorrowStart, $tomorrowEnd])
            ->count();

        //Tomorrow's Check-outs
        $data['tomorrowCheckOuts'] = (clone $activeBookingQuery)
            ->whereBetween('check_out', [$tomorrowStart, $tomorrowEnd])
            ->count();

        //Tomorrow's Occupancy
        $totalProperties = (clone $propertyQuery)
            ->where('status', 'active')
            ->count();

        $occupiedProperties = (clone $activeBookingQuery)
            ->where('check_in', '<', $tomorrowEnd)
            ->where('check_out', '>', $tomorrowStart)
            ->distinct('property_id')
            ->count('property_id');

        $data['tomorrowOccupancy'] = $totalProperties > 0
            ? round(($occupiedProperties / $totalProperties) * 100, 1)
            : 0;

        //Next 7 Days Operations

        $nextSevenDaysStart = $tomorrowStart->copy();
        $nextSevenDaysEnd = $today->copy()->addDays(7)->endOfDay();
        //Upcoming Check-ins
        $data['upcomingCheckIns'] = (clone $activeBookingQuery)
            ->whereBetween('check_in', [$nextSevenDaysStart, $nextSevenDaysEnd])
            ->count();

        //Upcoming Check-outs
        $data['upcomingCheckOuts'] = (clone $activeBookingQuery)
            ->whereBetween('check_out', [$nextSevenDaysStart, $nextSevenDaysEnd])
            ->count();

        //Average Occupancy (Next 7 Days)
        $upcomingBookings = (clone $activeBookingQuery)
            ->where('check_in', '<', $nextSevenDaysEnd)
            ->where('check_out', '>', $nextSevenDaysStart)
            ->get(['property_id', 'check_in', 'check_out']);

        $upcomingBookings->transform(function ($booking) {

            $booking->check_in = Carbon::parse($booking->check_in);
            $booking->check_out = Carbon::parse($booking->check_out);

            return $booking;

        });

        $totalOccupancyPercentage = 0;

        for ($date = $nextSevenDaysStart->copy(); $date->lte($nextSevenDaysEnd); $date->addDay()) {

            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $occupiedProperties = $upcomingBookings
                ->filter(function ($booking) use ($dayStart, $dayEnd) {

                    return $booking->check_in->lt($dayEnd)
                        && $booking->check_out->gt($dayStart);

                })
                ->pluck('property_id')
                ->unique()
                ->count();

            $totalOccupancyPercentage += $totalProperties > 0
                ? ($occupiedProperties / $totalProperties) * 100
                : 0;
        }

        $data['averageOccupancy'] = round($totalOccupancyPercentage / 7, 1);
    }

    private function loadTodayBusinessStats(&$data, $propertyQuery, $bookingQuery, $expenseQuery, $paymentQuery)
    {
        $today = Carbon::today();

        // Today's Booking Statistics
        $bookingStats = (clone $bookingQuery)
            ->whereDate('check_in', $today)
            ->selectRaw('
                COUNT(*) as total_bookings,
                COALESCE(SUM(total_guests),0) as total_guests
            ')
            ->first();

        // Today's Revenue
        $todayRevenue = (clone $paymentQuery)
            ->whereDate('payment_date', $today)
            ->sum('paid_amount');

        // Today's Expense
        $todayExpense = (clone $expenseQuery)
            ->whereDate('expense_date', $today)
            ->sum('amount');

        // Today's Business
        $data['todayBookings'] = $bookingStats->total_bookings ?? 0;

        $data['todayGuests'] = $bookingStats->total_guests ?? 0;

        $data['todayRevenue'] = $todayRevenue;

        $data['todayExpense'] = $todayExpense;

        $data['todayProfit'] = $todayRevenue - $todayExpense;
    }


    // Section 3 : Monthly KPIs
    private function loadMonthlyStats(&$data, $propertyQuery, $bookingQuery, $expenseQuery, $paymentQuery){
        $monthStart = now()->startOfMonth()->startOfDay();
        $monthEnd = now()->endOfDay();

        $data['monthlyBookings'] = (clone $bookingQuery)
            ->whereBetween('check_in', [$monthStart, $monthEnd])
            ->count();

        $data['monthlyRevenue'] = (clone $paymentQuery)
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->sum('paid_amount');

        $data['monthlyExpenses'] = (clone $expenseQuery)
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->sum('amount');

        $data['monthlyProfit'] = $data['monthlyRevenue'] - $data['monthlyExpenses'];

        $data['monthlyGuests'] = (clone $bookingQuery)
            ->whereBetween('check_in', [$monthStart, $monthEnd])
            ->sum('total_guests');

        $daysElapsed = now()->day;

        $data['averageDailyRevenue'] = $daysElapsed > 0
            ? $data['monthlyRevenue'] / $daysElapsed
            : 0;
    } 
    
    // Section: Occupancy & Revenue Treands Charts
    private function loadOccupancyRevenueCharts(&$data, $propertyQuery, $bookingQuery, $paymentQuery)
    {
        $labels = [];
        $occupancyData = [];
        $revenueData = [];

        $totalProperties = (clone $propertyQuery)->count();

        for ($i = 30; $i >= 1; $i--) {
            $date = now()->subDays($i);

            $labels[] = $date->format('d M');

            $occupiedProperties = (clone $bookingQuery)
                ->where('check_in', '<=', $date->copy()->endOfDay())
                ->where('check_out', '>', $date->copy()->startOfDay())
                ->distinct('property_id')
                ->count('property_id');

            $occupancyData[] = $totalProperties > 0
                ? round(($occupiedProperties / $totalProperties) * 100, 2)
                : 0;

            $revenueData[] = (clone $paymentQuery)
                ->whereDate('payment_date', $date->toDateString())
                ->sum('paid_amount');
        }

        $data['occupancyChartLabels'] = $labels;
        $data['occupancyChartData'] = $occupancyData;

        $data['revenueChartLabels'] = $labels;
        $data['revenueChartData'] = $revenueData;
    }

    // Section 6 - Top Performing Branches & Properties

    private function prepareTopRevenueData(&$data, $bookingQuery, $startDate, $endDate)
    {
        // Top 10 Branches By Revenue (Current Month)
        $topBranches = (clone $bookingQuery)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate]);
            })
            ->join('properties', 'offline_bookings.property_id', '=', 'properties.id')
            ->join('branches', 'properties.branch_id', '=', 'branches.id')
            ->select(
                'branches.id',
                'branches.name',
                DB::raw('SUM(offline_bookings.total_amount) as revenue')
            )
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $data['topBranchNames'] = $topBranches->pluck('name');
        $data['topBranchRevenue'] = $topBranches->pluck('revenue');

        // Top 10 Properties By Revenue (Current Month)
        $topProperties = (clone $bookingQuery)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate]);
            })
            ->join('properties', 'offline_bookings.property_id', '=', 'properties.id')
            ->join('branches', 'properties.branch_id', '=', 'branches.id')
            ->select(
                'properties.id',
                'properties.property_number',
                'branches.name as branch_name',
                'branches.location',
                DB::raw('SUM(offline_bookings.total_amount) as revenue')
            )
            ->groupBy(
                'properties.id',
                'properties.property_number',
                'branches.name',
                'branches.location'
            )
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $data['topPropertyNames'] = $topProperties->map(function ($property) {
            return $property->property_number . ' (' . $property->branch_name . ')';
        });

        $data['topPropertyRevenue'] = $topProperties->pluck('revenue');

        // Top 10 Branches By Bookings (Current Month)
        $topBranchesByBookings = (clone $bookingQuery)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate]);
            })
            ->join('properties', 'offline_bookings.property_id', '=', 'properties.id')
            ->join('branches', 'properties.branch_id', '=', 'branches.id')
            ->select(
                'branches.id',
                'branches.name',
                DB::raw('COUNT(offline_bookings.id) as total_bookings')
            )
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_bookings')
            ->limit(10)
            ->get();

        $data['topBranchBookingNames'] = $topBranchesByBookings->pluck('name');
        $data['topBranchBookings'] = $topBranchesByBookings->pluck('total_bookings');

        // Top 10 Properties By Bookings (Current Month)
        $topPropertiesByBookings = (clone $bookingQuery)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate]);
            })
            ->join('properties', 'offline_bookings.property_id', '=', 'properties.id')
            ->join('branches', 'properties.branch_id', '=', 'branches.id')
            ->select(
                'properties.id',
                'properties.property_number',
                'branches.name as branch_name',
                DB::raw('COUNT(offline_bookings.id) as total_bookings')
            )
            ->groupBy(
                'properties.id',
                'properties.property_number',
                'branches.name'
            )
            ->orderByDesc('total_bookings')
            ->limit(10)
            ->get();

        $data['topPropertyBookingNames'] = $topPropertiesByBookings->map(function ($property) {
            return $property->property_number . ' (' . $property->branch_name . ')';
        });

        $data['topPropertyBookings'] = $topPropertiesByBookings->pluck('total_bookings');
    }

    //Operations Center
    private function loadOperationsCenter(&$data, $bookingQuery)
    {
        $now = Carbon::now();

        $attentionQuery = clone $bookingQuery;

        $data['attentionRequired'] = $attentionQuery
            ->with(['property.branch', 'guests'])
            ->where(function ($query) use ($now) {

                // Active bookings requiring attention
                $query->where(function ($subQuery) use ($now) {
                    $subQuery->where('status', 'active')
                        ->where(function ($bookingQuery) use ($now) {
                            $bookingQuery
                                ->where('check_out', '<', $now)
                                ->orWhere(function ($paymentQuery) use ($now) {
                                    $paymentQuery
                                        ->whereColumn('paid_amount', '<', 'total_amount')
                                        ->where('check_in', '<=', $now);
                                });
                        });
                });

                // Completed bookings with pending payment
                $query->orWhere(function ($subQuery) {
                    $subQuery->where('status', 'completed')
                        ->whereColumn('paid_amount', '<', 'total_amount');
                });
            })
            ->get()
            ->map(function ($booking) use ($now) {

                $hasPendingPayment = $booking->paid_amount < $booking->total_amount;

                $isOverdueCheckout = $booking->status == 'active'
                    && Carbon::parse($booking->check_out)->lt($now);

                if ($hasPendingPayment && $isOverdueCheckout) {

                    $booking->attention_priority = 1;
                    $booking->attention_type = 'Payment Due + <br>Overdue Checkout';
                    $booking->attention_badge = 'danger';

                } elseif ($isOverdueCheckout) {

                    $booking->attention_priority = 2;
                    $booking->attention_type = 'Overdue Checkout';
                    $booking->attention_badge = 'warning';

                } else {

                    $booking->attention_priority = 3;
                    $booking->attention_type = 'Pending Payment';
                    $booking->attention_badge = 'yellow';
                }

                $booking->pending_amount = max(0, $booking->total_amount - $booking->paid_amount);

                return $booking;
            })
            ->sort(function ($firstBooking, $secondBooking) {

                if ($firstBooking->attention_priority == $secondBooking->attention_priority) {
                    return strtotime($firstBooking->check_out) <=> strtotime($secondBooking->check_out);
                }

                return $firstBooking->attention_priority <=> $secondBooking->attention_priority;
            })
            ->values();

        //Today's Operations
        $scheduleQuery = clone $bookingQuery;

        $data['todayOperations'] = $scheduleQuery
            ->with(['property.branch', 'guests'])
            ->where('status', 'active')
            ->where(function ($query) use ($now) {

                //Today's Check-ins
                $query->where(function ($subQuery) use ($now) {
                    $subQuery->whereDate('check_in', $now->toDateString())
                        ->where('check_in', '>=', $now);
                });

                //Today's Check-outs
                $query->orWhere(function ($subQuery) use ($now) {
                    $subQuery->whereDate('check_out', $now->toDateString())
                        ->where('check_out', '>=', $now);
                });

            })
            ->get()
            ->map(function ($booking) use ($now) {

                if (
                    Carbon::parse($booking->check_in)->isToday() &&
                    Carbon::parse($booking->check_in)->gte($now)
                ) {

                    $booking->operation_type = 'Check-in';
                    $booking->operation_badge = 'success';
                    $booking->operation_datetime = Carbon::parse($booking->check_in);

                } else {

                    $booking->operation_type = 'Check-out';
                    $booking->operation_badge = 'primary';
                    $booking->operation_datetime = Carbon::parse($booking->check_out);

                }

                $booking->operation_time = $booking->operation_datetime->format('h:i A');
                $booking->guest_name = optional($booking->guests->first())->name;

                return $booking;

            })
            ->sort(function ($firstBooking, $secondBooking) {

                return $firstBooking->operation_datetime <=> $secondBooking->operation_datetime;

            })
            ->values();
    }

    

}
