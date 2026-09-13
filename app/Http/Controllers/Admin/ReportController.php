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
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Exports\OfflineBookingListExport;
use App\Models\Expense;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\File;

class ReportController extends Controller
{
    public function summaryReport(Request $request)
    {
        $title = 'Report';

        $from = $request->from_date;
        $to   = $request->to_date;
        $branchId = $request->branch_id;
        $branchProperties = Property::where('branch_id', $branchId)->get();
        $propertyId = $request->property_id;

        if (Auth::user()->isSuperAdmin()) {
            $bookingQuery = OfflineBooking::with(['property.branch.city'])->whereIn('offline_bookings.status', ['active', 'completed']);
            $expenseQuery = Expense::query();
            $branches = Branch::with(['city'])
                ->where('status', 'active')
                ->get();
        } else {

            $bookingQuery = OfflineBooking::with(['property.branch.city'])->whereHas('property', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            })->whereIn('offline_bookings.status', ['active', 'completed']);
            $expenseQuery = Expense::whereHas('property', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });
            $branches = Branch::with(['city'])
                ->where('id', Auth::user()->branch_id)
                ->where('status', 'active')
                ->get();
        }

        if (!empty($branchId) && Auth::user()->isSuperAdmin()) {
            $bookingQuery->whereHas('property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $expenseQuery->whereHas('property', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        if (!empty($from) && !empty($to)) {
            $bookingQuery->whereBetween('check_in', [$from, $to]);
            $expenseQuery->whereBetween('expense_date', [$from, $to]);
        } elseif ($from) {
            $bookingQuery->whereDate('check_in', '=', $from);
            $expenseQuery->whereDate('expense_date', '=', $from);
        } elseif ($to) {
            $bookingQuery->whereDate('check_in', '=', $to);
            $expenseQuery->whereDate('expense_date', '=', $to);
        }

        if (!empty($propertyId)) {
            $bookingQuery->where('property_id', $propertyId);
            $expenseQuery->where('property_id', $propertyId);
        }

        // echo "<pre>";
        // print_r($expenseQuery->toRawSql());
        // exit;

        $bookingIds = (clone $bookingQuery)->pluck('id');
        $paymentQuery = OfflineBookingPayment::whereIn('booking_id', $bookingIds);

        $totalBookingAmount = $bookingQuery->sum('total_amount');
        $totalPaidAmount = $paymentQuery->sum('paid_amount');
        $totalExpense = $expenseQuery->sum('amount');
        $remainingAmount = $totalBookingAmount - $totalPaidAmount;
        $profit = $totalBookingAmount - $totalExpense;

        return view('admin.reports.index', compact(
            'title',
            'branches',
            'totalBookingAmount',
            'totalPaidAmount',
            'totalExpense',
            'remainingAmount',
            'profit',
            'from',
            'to',
            'branchProperties',
            'propertyId',
            'branchId'
        ));
    }
}