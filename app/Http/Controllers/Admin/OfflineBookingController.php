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
class OfflineBookingController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('offline-booking.view'), 403);

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;
        $branchId =  $request->branch_id;
        $data['branchProperties'] = Property::where('branch_id', $branchId)->get();
        $data['propertyId'] = $propertyId = $request->property_id;
        $keyword = $request->keyword;

        $data['title'] = 'Bookings';
        $data['create_title'] = 'Booking';
        if (Auth::user()->isSuperAdmin()) {
            $query = OfflineBooking::with(['property.branch.city']);
            $data['branches'] = Branch::with(['city'])
                ->where('status', 'active')
                ->get();
        } else {

            $query = OfflineBooking::with(['property.branch.city'])->whereHas('property', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });

            $data['branches'] = Branch::with(['city'])
                ->where('id', Auth::user()->branch_id)
                ->where('status', 'active')
                ->get();
        }
        $query = $query->leftJoin('offline_booking_guests as g', function ($join) {
                $join->on('g.booking_id', '=', 'offline_bookings.id')
                    ->whereRaw('g.id = (
                 SELECT MIN(id) 
                 FROM offline_booking_guests 
                 WHERE booking_id = offline_bookings.id
             )');
            })
            ->leftJoin('properties as pr', 'pr.id', '=', 'offline_bookings.property_id')
            ->leftJoin('branches as br', 'br.id', '=', 'pr.branch_id')
            ->leftJoin('cities as c', 'c.id', '=', 'br.city_id')
            ->whereNull('offline_bookings.deleted_at')
            ->select(
                'offline_bookings.*',
                'pr.property_number',
                'br.name as branch_name',
                'br.location',
                'br.pincode',
                'c.name as city_name',
                'c.state',
                'g.name as guest_name',
                'g.phone as guest_phone'
            )
            ->orderBy('id','DESC')
            ->groupBy('offline_bookings.id');

        if ($fromDate && $toDate) {

            $query->where(function ($q) use ($fromDate, $toDate) {
                $form = date('Y-m-d', strtotime($fromDate));
                $to = date('Y-m-d', strtotime($toDate));

                $q->whereDate('offline_bookings.check_in', '>=', $form)
                    ->whereDate('offline_bookings.check_out', '<=', $to);
            });
        } elseif ($fromDate) {
            $form = date('Y-m-d', strtotime($fromDate));
            $query->whereDate('offline_bookings.check_in', '=', $form);
        } elseif ($toDate) {
            $to = date('Y-m-d', strtotime($toDate));
            $query->whereDate('offline_bookings.check_out', '=', $to);
        }

        if (!empty($branchId) && Auth::user()->isSuperAdmin()) {
            $query->where('pr.branch_id', $branchId);
        }

        if (!empty($propertyId)) {
            $query->where('offline_bookings.property_id', $propertyId);
        }

        // Keyword Search
        if (!empty($keyword)) {
           
            $query->where(function ($q) use ($keyword) {

                $q->where('offline_bookings.show_booking_id', 'like', "%$keyword%")
                    ->orWhere('pr.property_number', 'like', "%$keyword%")
                    ->orWhere('br.name', 'like', "%$keyword%")
                    ->orWhere('br.location', 'like', "%$keyword%")
                    ->orWhere('br.pincode', 'like', "%$keyword%")
                    ->orWhere('c.name', 'like', "%$keyword%")
                    ->orWhere('c.state', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.total_guests', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.total_amount', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.paid_amount', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.source', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.payment_mode', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.cash_received_by', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.per_day_price', 'like', "%$keyword%")

                    ->orWhere('offline_bookings.booking_days', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.transferred_to_owner', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.early_checkin_charges', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.late_checkout_charges', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.damage_charges', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.total_charges', 'like', "%$keyword%")
                    ->orWhere('offline_bookings.by_refernece', 'like', "%$keyword%")

                    ->orWhere('g.name', 'like', "%$keyword%")
                    ->orWhere('g.phone', 'like', "%$keyword%");
                    // ->orWhere('p.transferred_owner', 'like', "%$keyword%")
                    // ->orWhere('p.receivedby', 'like', "%$keyword%")
                    // ->orWhere('p.payment_mode', 'like', "%$keyword%")                    
                    // ->orWhere('p.transaction_id', 'like', "%$keyword%");
            });
        }

        if ($request->ajax()) {

            return DataTables::of($query)

                ->addIndexColumn()
                ->addColumn('property_name', function ($row) {
                    return $row->property_number ?? 'N/A';
                })
                ->addColumn('branch_name', function ($row) {
                    if (!$row->branch_name) {
                        return 'N/A';
                    }

                    return $row->branch_name . ', '
                        . $row->location . ', '
                        . $row->city_name . ', '
                        . $row->state . ' - '
                        . $row->pincode;
                })
                ->editColumn('check_in', function ($row) {
                    return \Carbon\Carbon::parse($row->check_in)->format('d-m-Y h:i A');
                })

                ->editColumn('check_out', function ($row) {
                    return \Carbon\Carbon::parse($row->check_out)->format('d-m-Y h:i A');
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('guest', function ($row) {
                    return $row->guest_name . ' (' . $row->guest_phone . ')';
                })

                ->addColumn('action', function ($row) {
                    return $this->generateActionButtons($row);
                })

                ->rawColumns(['action'])

                ->make(true);
        }

        return view('admin.offlinebooking.list', $data);
    }



    private function generateActionButtons($booking)
    {
        $html = '';

        $createdDate = Carbon::parse($booking->created_at)->startOfDay();
        $checkOutDate = Carbon::parse($booking->created_at)->startOfDay();
        $today = now()->startOfDay();
        if (auth()->user()->can('offline-booking.edit') &&  $createdDate->diffInDays($today) < setting_value('booking_edit_time_in_day')) {
            $html .= '<a href="' . route('admin.offlinebookings.edit', encrypt($booking->id)) . '" 
                title="Edit Booking" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-edit"></i>
            </a> ';
        }

        if (auth()->user()->can('offline-booking.view')) {
            $html .= '<a href="' . route('admin.offlinebookings.view', encrypt($booking->id)) . '" 
                title="View Details" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-eye"></i>
            </a> ';
        }



        if ($booking->paid_amount < $booking->total_amount) {
            if (auth()->user()->can('offline-booking.payremainingamount')) {
                $html .= '<a href="' . route('admin.offlinebookings.payRemaining', encrypt($booking->id)) . '" 
                    class="btn btn-warning btn-xs mb-1" style="margin-right:2px;">
                        Pay Remaining Amount
                </a>';
            }
        } else {
            if (auth()->user()->can('offline-booking.paymenthistory')) {
                $html .= '<a href="' . route('admin.offlinebookings.payRemaining', encrypt($booking->id)) . '" 
                    class="btn btn-primary btn-xs mb-1" style="margin-right:2px;">
                        Payment History
                </a>';
            }
        }


        if (
            auth()->user()->can('offline-booking.extended') &&
            $createdDate->diffInDays($today) < setting_value('booking_extend_in_day') &&
            !Carbon::parse($booking->check_in)->isSameDay(Carbon::parse($booking->check_out))
        ) {
            $html .= '<a href="' . route('admin.offlinebookings.extend', encrypt($booking->id)) . '" 
                class="btn btn-info btn-xs mb-1" style="margin-right:2px;">
                Booking Extended
            </a>';
        }

        if(auth()->user()->can('offline-booking.complete-checkout') && $booking->status == 'active'){
            $bookingId = encrypt($booking->id);
            $html .= '<button data-booking_id="' . $bookingId . '" class="btn btn-success btn-xs mb-1 complete-checkout" style="margin-right:2px;">
                Complete Checkout
            </button>';
        }

        $html .= '<a href="' . url('bookings/'. encrypt($booking->id) .'/invoice') . '" 
            class="btn btn-dark btn-xs mb-1" style="margin-right:2px;">
            Download Invoice
        </a>';
        if (auth()->user()->can('offline-booking.cancel') && $booking->status == 'active') {
            $bookingId = encrypt($booking->id);
            $html .= '<button data-booking_id="' . $bookingId . '" class="btn btn-danger btn-xs mb-1 cancel-booking" style="margin-right:2px;">
                Cancel Booking
            </button>';
        }
        if (auth()->user()->can('offline-booking.delete')) {
            $id = encrypt($booking->id);
            $url = route('admin.offlinebookings.destroy', $id);
            $tableid = 'offlinebookingTable';

            $html .= '  <button type="button" class="btn btn-danger btn-xs delete-record" 
                data-id="' . $id . '"  data-url="' . $url . '"  data-tableid="' . $tableid . '"  data-title="booking" 
                title="Delete Booking" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-trash"></i>
            </button>';
        }

        return $html;
    }


    // Show create form
    public function create()
    {
        $title = 'Add Offline Booking';
        if(Auth::user()->isSuperAdmin()){
            $bookings = OfflineBooking::select('check_in', 'check_out')->get();
            $branches = Branch::with(['city'])->where('status','active')->get();
        }
        else{
            $bookings = OfflineBooking::select('check_in', 'check_out')->get();
            $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
        }

        $disabledDates = [];

        foreach ($bookings as $booking) {
            $disabledDates[] = [
                'from' => $booking->check_in,
                'to' => $booking->check_out
            ];
        }
        return view('admin.offlinebooking.add',  compact('title', 'disabledDates','branches'));
    }

    

    // Store or Update Bookings
    public function storeOrUpdate(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $isUpdate = $request->booking_id;

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'property_no' => 'required|exists:properties,id',
            'total_guests' => 'required|integer|min:1',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',

            'per_day_price' => 'required|numeric|min:1',
            'booking_days' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:1',
            'paid_amount' => 'required|numeric|min:0',

            'guests' => 'required|array|min:1',
            'guests.0.name'  => 'required|string|max:255',
            'guests.0.phone' => 'required|digits:10',

            'guests.*.name'  => 'nullable|string|max:255',
            'guests.*.phone' => 'nullable|digits:10',
            'guests.*.aadhaar' => 'nullable|array',
            'guests.*.aadhaar.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        $propertyId = $request->property_no;
        $checkIn  = Carbon::parse($request->check_in)->format('Y-m-d H:i:s');
        $checkOut = Carbon::parse($request->check_out)->format('Y-m-d H:i:s');


        $query = DB::table('offline_bookings')
            ->where('property_id', $propertyId)
            ->whereNull('deleted_at')
            ->where('status', 'active');

        // ✅ Update case: apni booking ko ignore karo
        if (!empty($isUpdate)) {
            $query->where('id', '!=', $isUpdate);
        }

        // ✅ Overlap check (correct logic)
        $query->where(function ($q) use ($checkIn, $checkOut) {
            $q->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn);
        });

        $exists = $query->exists();



        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Property already booked in this time range!'
            ]);
        }
        // =========================
        // CREATE / UPDATE BOOKING
        // =========================

        $property = Property::findOrFail($propertyId);
        
        // from frontend
        $guestCount = (int) $request->total_guests;
        $daysCount = (int) $request->booking_days;
        $perDayPrice = (float) $request->per_day_price;
        $extraGuestCharge = (float) $request->extra_guest_charge;
        
        // calculate from database
        // $guestCount = (int) count($request->guest['name']);
        // $daysCount = (int) calculateBookingDays($request->check_in, $request->check_out);
        // $perDayPrice = (float) $property->base_price;
        // $extraGuestCharge = (float) (max(0, $guestCount - $property->default_guests) * $property->extra_guest_charge * $daysCount);
        
        $baseAmount = $perDayPrice * $daysCount;
        $otherCharges = (float) ($request->early_checkin_charges ?? 0) +  (float) ($request->late_checkout_charges ?? 0) + (float) ($request->damage_charges ?? 0);
        $extraTotal = $extraGuestCharge + $otherCharges;

        $amount = $baseAmount + $extraTotal;
        

        if ($isUpdate) {

            $booking = OfflineBooking::findOrFail($isUpdate);

            $booking->update([
                'property_id' => $request->property_no,
                'total_guests' => $request->total_guests,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'by_refernece' => $request->by_refernece,
                'source' => $request->source,
                'payment_mode' => $request->payment_mode,
                'cash_received_by' => $request->cash_received_by,
                'per_day_price' => $perDayPrice,
                'booking_days' => $daysCount,
                'total_amount' => $amount,
                'paid_amount' => $request->paid_amount,
                'transferred_to_owner' => $request->transferred_to_owner,
                'extra_guest_charge' => $extraGuestCharge,
                'early_checkin_charges' => $request->early_checkin_charges,
                'late_checkout_charges' => $request->late_checkout_charges,
                'damage_charges' => $request->damage_charges,
                'total_charges' => $extraTotal,
            ]);
        } else {
            
            
            $bookingId = OfflineBooking::insertGetId([
                'property_id' => $request->property_no,
                'total_guests' => $request->total_guests,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'by_refernece' => $request->by_refernece,
                'source' => $request->source,
                'payment_mode' => $request->payment_mode,
                'cash_received_by' => $request->cash_received_by,
                'per_day_price' => $perDayPrice,
                'booking_days' => $daysCount,
                'total_amount' => $amount,
                'paid_amount' => $request->paid_amount,
                'transferred_to_owner' => $request->transferred_to_owner,
                'extra_guest_charge' => $extraGuestCharge,
                'early_checkin_charges' => $request->early_checkin_charges,
                'late_checkout_charges' => $request->late_checkout_charges,
                'damage_charges' => $request->damage_charges,
                'total_charges' => $extraTotal,
                'added_by' => auth()->id(),

            ]);

            $showBookingId = 'BOK' .date('Y') .str_pad($bookingId, 5, '0', STR_PAD_LEFT);
            OfflineBooking::where('id', $bookingId)->update([
                'show_booking_id' => $showBookingId
            ]);

            $booking = OfflineBooking::findOrFail($bookingId);
        }

        // =========================
        // SCREENSHOT UPLOAD
        // =========================
        if ($request->hasFile('owner_payment_screenshot')) {
            $file = $request->file('owner_payment_screenshot');
            
            // Unique filename banayein
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // File ko seedhe aapke root `storage/uploads/bookings` folder mein bhejlein
            $file->move(storage_path('uploads/bookings'), $filename);
            
            // Database ke liye sahi path set karein
            $path = 'uploads/bookings/' . $filename;

            // Database ko naye path se update karein
            $booking->update([
                'owner_payment_screenshot' => $path
            ]);
        }

        // =========================
        // GUESTS SAVE
        // =========================
        $existingGuestIds = [];

        foreach ($request->guests as $guest) {

            // skip empty
            if (empty($guest['name']) && empty($guest['phone'])) {
                continue;
            }

            // 👉 old files (array bana lo)
            $aadhaarPaths = [];

            if (!empty($guest['aadhaar_old'])) {
                $aadhaarPaths = explode(',', $guest['aadhaar_old']);
            }

            // 👉 new multiple files upload
            if (isset($guest['aadhaar']) && is_array($guest['aadhaar'])) {
                foreach ($guest['aadhaar'] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        // Ek unique naam banayein taaki file overwrite na ho
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        
                        // Ye directly aapke dikhaye gaye `storage/uploads/aadhaar` folder mein file bhej dega
                        $file->move(storage_path('uploads/aadhaar'), $filename);
                        
                        // Database mein save karne ke liye path array mein daalein
                        $aadhaarPaths[] = 'uploads/aadhaar/' . $filename;
                    }
                }
            }

            // 👉 final comma separated string
            $aadhaarPathString = !empty($aadhaarPaths) ? implode(',', $aadhaarPaths) : null;

            if (!empty($guest['id'])) {

                // Update
                OfflineBookingGuest::where('id', $guest['id'])->update([
                    'name' => $guest['name'] ?? null,
                    'phone' => $guest['phone'] ?? null,
                    'aadhaar' => $aadhaarPathString
                ]);

                $existingGuestIds[] = $guest['id'];
            } else {

                // Create
                $newGuest = OfflineBookingGuest::create([
                    'booking_id' => $booking->id,
                    'name' => $guest['name'] ?? null,
                    'phone' => $guest['phone'] ?? null,
                    'aadhaar' => $aadhaarPathString
                ]);

                $existingGuestIds[] = $newGuest->id;
            }
        }

        OfflineBookingGuest::where('booking_id', $booking->id)
            ->whereNotIn('id', $existingGuestIds)
            ->delete();

        $payment = OfflineBookingPayment::where('booking_id', $booking->id)
            ->orderBy('id', 'asc')
            ->first();

        if ($payment) {

            $payment->update([
                'payment_screenshot' => $booking->owner_payment_screenshot,
                'payment_mode' => $booking->payment_mode,
                'paid_amount' => $booking->paid_amount,
                'transferred_owner' => $request->transferred_to_owner ?? 'No',
                'receivedby' => $request->cash_received_by ?? null,
            ]);
        } else {

            
            $paymentId = OfflineBookingPayment::insertGetId([
                'booking_id' => $booking->id,
                'paid_amount' => $request->paid_amount,
                'payment_screenshot' => $booking->owner_payment_screenshot,
                'payment_mode' => $booking->payment_mode,
                'transferred_owner' => $request->transferred_to_owner ?? 'No',
                'receivedby' => $request->cash_received_by ?? null,

                'payment_date' => now(),
            ]);

            $transactionId = 'INV' .date('Y') .str_pad($paymentId, 5, '0', STR_PAD_LEFT);
            OfflineBookingPayment::where('id', $paymentId)->update([
                'transaction_id' => $transactionId
            ]);
        }

        // =========================
        // OPTIONAL: EXTENSION TABLE
        // =========================
        if ($request->extension_days) {
            OfflineBookingExtension::create([
                'booking_id' => $booking->id,
                'days' => $request->extension_days,
                'price' => $request->extension_price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $isUpdate ? 'Booking updated successfully.' : 'Booking created successfully.',

        ]);
    }

    // Edit form
    public function edit($id)
    {
        try {

            $title = 'Edit Booking';

            $bookingId = decrypt($id);

            $booking = OfflineBooking::with([
                'property.branch.city',
                'guests',
                'payments',
                'extensions'
            ])->findOrFail($bookingId);

            if(Auth::user()->isSuperAdmin()){
                $bookings = OfflineBooking::select('check_in', 'check_out')->get();
                $branches = Branch::with(['city'])->where('status','active')->get();
            }
            else{
                $bookings = OfflineBooking::select('check_in', 'check_out')->get();
                $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
            }

            
            $bookingPayment = OfflineBookingPayment::where('booking_id', $bookingId)
                ->first();


            $disabledDates = [];

            foreach ($bookings as $book) {
                $disabledDates[] = [
                    'from' => $book->check_in,
                    'to' => $book->check_out
                ];
            }

            return view('admin.offlinebooking.edit', compact('branches','booking', 'title', 'disabledDates', 'bookingPayment'));
        } catch (\Exception $e) {
            abort(404);
        }
    }


    public function view($id)
    {
        try {

            $title = 'View Booking';

            $bookingId = decrypt($id);

            $booking = OfflineBooking::with([
                'guests',
                'payments',
                'extensions'
            ])->findOrFail($bookingId);


            // dd($booking);
            $bookings = OfflineBooking::select('check_in', 'check_out')->get();


            $bookingPayments = OfflineBookingPayment::where('booking_id', $bookingId)
                ->get();


            return view('admin.offlinebooking.view', compact('booking', 'title',  'bookingPayments'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    

    public function getBookedDates(Request $request)
    {
        $bookings = OfflineBooking::with('extensions')
            ->where('property_no', $request->property_no)
            ->get();

        $dates = [];

        foreach ($bookings as $b) {

            $start = Carbon::parse($b->check_in);

            $maxExtension = $b->extensions->max('new_checkout');

            $end = $maxExtension
                ? Carbon::parse($maxExtension)
                : Carbon::parse($b->check_out);

            $dates[] = [
                'from' => $start->format('Y-m-d'),
                'to'   => $end->format('Y-m-d') // no subDay()
            ];
        }

        return response()->json($dates);
    }


    // Delete branch
    public function destroy($id)
    {
        // Permission check
        if (!auth()->user()->can('branch.delete')) {
            return response()->json([
                'status' => false,
                'message' => 'User does not have permission to delete this branch.',
            ], 403);
        }

        try {
            $bookingId = decrypt($id);
            $bookingId = OfflineBooking::findOrFail($bookingId);
            // Delete main bookingId
            $bookingId->delete();

            return response()->json([
                'status' => true,
                'message' => 'Booking deleted successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Booking deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete booking. Please try again.',
            ], 500);
        }
    }


    public function payRemaining($id)
    {
        try {
            $title = 'Pay Remaining Amount';
            $bookingId = decrypt($id);

            $booking = OfflineBooking::findOrFail($bookingId);

            $bookingPayments = OfflineBookingPayment::where('booking_id', $bookingId)->orderBy('id', 'DESC')
                ->get();

            return view('admin.offlinebooking.pay-remaining-amount', compact('booking', 'title', 'bookingId', 'bookingPayments'));
        } catch (\Exception $e) {
            return redirect()->route('admin.offlinebooking.index')
                ->with('error', __('Failed to retrieve booking.'));
        }
    }

    public function storePayment(Request $request)
    {
        $booking = OfflineBooking::findOrFail($request->booking_id);

        $remaining = $booking->total_amount - $booking->paid_amount;

        $request->validate([
            'pay_amount' => ['required', 'numeric', 'min:1', 'max:' . $remaining],
            'payment_mode' => ['required', 'string'],
            'payment_screenshot' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf'],
        ]);

        $path = null;
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            
            // File ka ek unique naam banayein
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Directly aapke dikhaye gaye `storage/uploads/bookings` folder mein move karein
            $file->move(storage_path('uploads/bookings'), $filename);
            
            // Database mein save karne ke liye path variable
            $path = 'uploads/bookings/' . $filename;
        }

        $transactionId = 'INV' . random_int(10000, 99999);
        OfflineBookingPayment::create([
            'booking_id' => $booking->id,
            'paid_amount' => $request->pay_amount,
            'payment_mode' => $request->payment_mode,
            'payment_mode' => $request->payment_mode,
            'receivedby' => $request->cash_received_by,
            'transferred_owner' => $request->transferred_owner,
            'transaction_id' => $transactionId,
            'payment_date' =>  now(),
            'payment_screenshot' => $path,
        ]);

        // update booking paid amount
        $booking->increment('paid_amount', $request->pay_amount);

        return back()->with('success', 'Payment added successfully');
    }



    public function editPayment($id)
    {
        $paymentId = decrypt($id);
        $title = 'Edit Remaining Amount';
        $payment = OfflineBookingPayment::findOrFail($paymentId);
        $booking = OfflineBooking::findOrFail($payment->booking_id);

        $edit = 'Yes';
        return view('admin.offlinebooking.edit-payment', compact('payment', 'title', 'booking', 'edit'));
    }



    public function updatePayment(Request $request, $id)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;
        DB::beginTransaction();

        $payment = OfflineBookingPayment::findOrFail(decrypt($id));
        $booking = OfflineBooking::findOrFail($payment->booking_id);

        // check if amount changed
        $isAmountChanged = $payment->paid_amount != $request->paid_amount;

        if ($isAmountChanged) {
            // revert old amount
            $booking->decrement('paid_amount', $payment->paid_amount);


            $remaining = $booking->total_amount - $booking->paid_amount;

            $request->validate([
                'paid_amount' => ['required', 'numeric', 'min:1', 'max:' . $remaining],
                'payment_mode' => 'required'
            ]);
        } else {

            $request->validate([
                'paid_amount' => ['required', 'numeric', 'min:1'],
                'payment_mode' => 'required'
            ]);
        }

        $path = $payment->payment_screenshot;

        // file replace
        if ($request->hasFile('payment_screenshot')) {

            // 1. Agar puraani file ka path exist karta hai, toh use root storage folder se delete karein
            if (!empty($path)) {
                $oldFilePath = storage_path($path); // Isse full path banega: storage/uploads/bookings/filename.png
                
                if (File::exists($oldFilePath)) {
                    File::delete($oldFilePath); // Puraani file delete ho gayi
                }
            }

            // 2. Nayi file ko move karein
            $file = $request->file('payment_screenshot');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('uploads/bookings'), $filename);

            // 3. Naya path variable mein set karein
            $path = 'uploads/bookings/' . $filename;
        }

        // update payment
        $payment->update([
            'paid_amount' => $request->paid_amount,
            'payment_mode' => $request->payment_mode,
            'payment_screenshot' => $path,
            'receivedby' => $request->cash_received_by,
            'transferred_owner' => $request->transferred_owner,
        ]);


        if ($isAmountChanged) {
            $booking->increment('paid_amount', $request->paid_amount);
        }

        DB::commit();

        return redirect()->route('admin.offlinebookings.payRemaining', encrypt($booking->id))->with('success', 'Payment updated');
    }

    public function deletePayment($id)
    {
        $payment = OfflineBookingPayment::findOrFail(decrypt($id));

        $booking = OfflineBooking::findOrFail($payment->booking_id);

        $booking->decrement('paid_amount', $payment->paid_amount);

        if ($payment->payment_screenshot && Storage::disk('public')->exists($payment->payment_screenshot)) {
            Storage::disk('public')->delete($payment->payment_screenshot);
        }

        $payment->delete();

        return back()->with('success', 'Payment deleted');
    }

    public function searchGuest(Request $request)
    {
        $search = $request->search;

        return OfflineBookingGuest::where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%$search%")
                ->orWhere('phone', 'LIKE', "%$search%");
        })
            ->orderByRaw("CASE WHEN aadhaar IS NOT NULL THEN 0 ELSE 1 END") // aadhaar first
            ->orderByDesc('id') // latest bhi priority
            ->get()
            ->unique(function ($item) {
                return $item->name . '-' . $item->phone;
            })
            ->take(10)
            ->values();
    }

    public function exportOfflineBookingExcelList(Request $request)
    {
        $fromDate   = $request->from_date;
        $toDate     = $request->to_date;
        $branchId   = $request->branch_id;
        $propertyId = $request->property_id;
        $keyword    = $request->keyword;
        $type       = '';

        $fileName = 'Offline_Bookings';

        if ($fromDate) {
            $fileName .= '_' . ($fromDate ?: 'Start');
        }

        if ($toDate) {
            $fileName .= '_to_' . ($toDate ?: 'End');
        }

        if ($branchId) {
            $branch = Branch::find($branchId);
            if ($branch) {
                $fileName .= '_' . str_replace(' ', '_', $branch->name);
            }
        }

        if ($propertyId) {
            $property = Property::find($propertyId);
            if ($property) {
                $fileName .= '_' . $property->property_number;
            }
        }

        $fileName .= '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new OfflineBookingListExport(
                $fromDate,
                $toDate,
                $branchId,
                $propertyId,
                $keyword,
                $type
            ), 
            $fileName
        );
    }

    public function exportOfflineBookingCsvList(Request $request)
    {
        $fromDate   = $request->from_date;
        $toDate     = $request->to_date;
        $branchId   = $request->branch_id;
        $propertyId = $request->property_id;
        $keyword    = $request->keyword;
        $type       = 'csv';

        $fileName = 'Offline_Bookings';

        if ($fromDate) {
            $fileName .= '_' . ($fromDate ?: 'Start');
        }

        if ($toDate) {
            $fileName .= '_to_' . ($toDate ?: 'End');
        }

        if ($branchId) {
            $branch = Branch::find($branchId);
            if ($branch) {
                $fileName .= '_' . str_replace(' ', '_', $branch->name);
            }
        }

        if ($propertyId) {
            $property = Property::find($propertyId);
            if ($property) {
                $fileName .= '_' . $property->property_number;
            }
        }

        $fileName .= '_' . now()->format('Ymd_His') . '.csv';

        return Excel::download(
            new OfflineBookingListExport(
                $fromDate,
                $toDate,
                $branchId,
                $propertyId,
                $keyword,
                $type
            ),
            $fileName
        );
    }



    public function bookingExtended($id)
    {
        $title = 'Booking Extended';
        $bookingId = decrypt($id);

        $booking = OfflineBooking::findOrFail($bookingId);

        $bookingPayments = OfflineBookingPayment::where('booking_id', $bookingId)
            ->latest()
            ->get();

        $extensions = OfflineBookingExtension::where('booking_id', $booking->id)
            ->latest()
            ->get();

        $blockedRanges = [];

        $bookings = OfflineBooking::with('extensions')
            ->where('property_id', $booking->property_id)
            ->get();

        foreach ($bookings as $b) {

            $start = Carbon::parse($b->check_in);

            $maxExtension = $b->extensions->max('new_checkout');

            $end = $maxExtension
                ? Carbon::parse($maxExtension)
                : Carbon::parse($b->check_out);

            $blockedRanges[] = [
                'from' => $start->format('Y-m-d'),
                'to'   => $end->format('Y-m-d') // no subDay()
            ];
        }

        return view('admin.offlinebooking.booking-extend', compact('booking', 'title', 'bookingId', 'bookingPayments', 'extensions', 'blockedRanges'));
    
    }


    public function extendStay(Request $request)
    {
        $booking = OfflineBooking::findOrFail($request->booking_id);

        $old = Carbon::parse($booking->check_out);
        $new = Carbon::parse($request->new_check_out);

        if ($new->lessThanOrEqualTo($old)) {
            return back()->with('error', 'Invalid date');
        }

        $conflict = OfflineBooking::where('property_id', $booking->property_id)
            ->where('id', '!=', $booking->id)
            ->where(function ($q) use ($old, $new) {
                $q->where('check_in', '<', $new)
                    ->where('check_out', '>', $old);
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Property already booked for selected dates');
        }


        $days = $old->diffInDays($new);
        $extraAmount = $request->extra_days * $request->per_day_price;

        OfflineBookingExtension::create([
            'booking_id'   => $booking->id,
            'old_checkout' => $old,
            'new_checkout' => $new,
            'extra_days'   => $days,
            'extra_amount' => $extraAmount,
        ]);

        $booking->check_out = $new;
        $booking->total_amount += $extraAmount;
        $booking->save();


        return redirect()->route('admin.offlinebookings.extend', encrypt($booking->id))->with('success', 'Stay extended successfully');
    }


    public function deleteGuestDoc(Request $request)
    {
        $guest = OfflineBookingGuest::find($request->guest_id);

        if (!$guest || !$guest->aadhaar) {
            return back()->with('error', 'Guest not found');
        }

        $files = explode(',', $guest->aadhaar);


        $files = array_filter($files, function ($f) use ($request) {
            return trim($f) !== trim($request->file);
        });


        if (\Storage::disk('public')->exists($request->file)) {
            \Storage::disk('public')->delete($request->file);
        }


        $guest->aadhaar = !empty($files) ? implode(',', $files) : null;
        $guest->save();

        return back()->with('success', 'Document deleted successfully');
    }

    public function updateStatus(Request $request){
        $bookingId = null;
        if(!empty($request->booking_id)){
            $bookingId = decrypt($request->booking_id);

            $request->merge([
                'id' => $bookingId
            ]);
        }
        $request->validate([
            'id' => 'required|exists:offline_bookings,id',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $uid = OfflineBooking::where('id', $bookingId)->update([
            'status' => $request->status,
        ]);

        if($uid){
            return response()->json([
                'status' => true,
                'message' => 'Status Updated Successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong. Please try again!',
        ], 403);
        
    }
}
