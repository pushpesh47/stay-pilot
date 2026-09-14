<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Branch;
use App\Models\PropertyType;
use App\Models\User;
use App\Models\City;
use App\Models\OfflineBooking;
use App\Models\OfflineBookingExtension;
use App\Models\OfflineBookingGuest;
use App\Models\OfflineBookingPayment;
use App\Models\GatewayPayment;
use Auth;

use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Razorpay\Api\Api as Razorpay;
use Razorpay\Api\Errors\SignatureVerificationError;

class HomeController extends Controller
{
    public function homepage(Request $request){
        $data['title'] = "";
        $data['branches'] = Branch::with(['images','properties.images','city'])->where('status','active')->get();
        return view('frontend.homepage', $data);
    }

    public function properties(Request $request){
        
        if(!empty($request->search_city)){
            $data['search']['city'] = $request->search_city;
            $data['search']['branches'] = Branch::where('city_id', $data['search']['city'])
                ->where('status','active')->orderBy('name', 'ASC')->get();
        }
        if(!empty($request->search_checkin)){
            $data['search']['checkin'] = $request->search_checkin;
        }
        if(!empty($request->search_checkout)){
            $data['search']['checkout'] = $request->search_checkout;
        }
        if(!empty($request->search_branch)){
            $data['search']['selected_branch'] = Branch::with(['images','properties.images','city'])->find($request->search_branch);
        }
        if(!empty($request->search_guests)){
            $data['search']['guest'] = $request->search_guests;
        }

        $data['title'] = "Properties";
        $data['branches'] = Branch::with(['images','properties.images','city'])->where('status','active')->get();


        return view('frontend.properties', $data);
    }
    

    public function propertyDetails(Request $request, $id){
        $data['title'] = "Property Details";
        $propertyId = decrypt($id);
        $data['branches'] = Branch::with(['images','properties.images','city'])->where('status','active')->get();
        $data['property'] = Property::with(['propertyType', 'branch.city', 'images'])->findOrFail($propertyId);
        $data['checkIn']  = now()->setTimeFromTimeString($data['property']->branch->check_in_time);
        $data['checkOut'] = now()->addDay()->setTimeFromTimeString($data['property']->branch->check_out_time);
        // echo "<pre>";
        // print_r($data['property']->toArray());
        // exit;
        return view('frontend.property-details', $data);
    }

    public function checkPropertyAvailability(Request $request)
    {
        $request->validate([
            'property_id'   => 'required',
            'check_in'  => 'required',
            'check_out' => 'required',
        ]);

        $propertyId = decrypt($request->property_id);

        $checkIn  = Carbon::parse($request->check_in)->format('Y-m-d H:i:s');
        $checkOut = Carbon::parse($request->check_out)->format('Y-m-d H:i:s');

        $exists = DB::table('offline_bookings')
            ->where('property_id', $propertyId)
            ->whereNull('deleted_at')
            ->where('status', 'active')
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn);
            })
            ->exists();

        return response()->json([
            'status' => !$exists,
            'message' => $exists
                ? 'Property already booked in this time range!'
                : 'Property available'
        ]);
    }

    public function bookingSignup(Request $request){

        //  print_r($request->all());exit;
        return redirect('register')->with([
            'property_id' => $request->property_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'days_count' => $request->days_count,
            'total_cost' => $request->total_cost,
            'guest_count' => $request->guest_count,
        ]);
    }

    public function register(Request $request)
    {
        if(auth()->check()){
            return redirect('/');
        }
        $booking = [
            'property_id'     => session('property_id'),
            'check_in'    => session('check_in'),
            'check_out'   => session('check_out'),
            'days_count'  => session('days_count'),
            'total_cost'  => session('total_cost'),
            'guest_count' => session('guest_count'),
        ];
        // $booking = [
        //     'property_id'     => encrypt(1),
        //     'check_in'    => "2026-06-22 12:00",
        //     'check_out'   => "2026-06-24 12:00",
        //     'days_count'  => 2,
        //     'total_cost'  => 1,
        //     'guest_count' => 1,
        // ];

        return view('frontend.register', compact('booking'));
    }

    

    public function registerGuest(Request $request){
        $propertyId = null;

        $validatedUserData = $request->validate([
            'name'            => 'required|string|max:255',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email,NULL,id,deleted_at,NULL',
            'password'        => 'required|string|min:8',
            'mobile'          => 'required|string|max:15',

        ]);

        if(!empty($request->property_id)){
            $propertyId = decrypt($request->property_id);
            $request->merge([
                'property_no' => $propertyId
            ]);
            $validatedGuestData = $request->validate([
                'property_no' => 'required|exists:properties,id',
                'guest_count' => 'required|integer|min:1',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'days_count' => 'required|integer|min:1',

                'guests' => 'required|array|min:1',
                'guests.*.name' => 'required|string|max:255',
                'guests.*.phone' => 'required|digits:10',
                'guests.*.aadhaar' => 'required|array|min:1',
                'guests.*.aadhaar.*' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',

            ]);
        }

        $validatedUserData['password'] = bcrypt($validatedUserData['password']);
        $user =  User::create($validatedUserData);
        Auth::login($user);
        
        
        if(empty($propertyId)){
            return response()->json([
                'success' => true,
                'message' => "User registered successfully!",
                'redirect' => true,
                'redirect_url' => url('/')
            ]);
        }else{
            $razorpay = new Razorpay(
                setting_value('razorpay_key'),
                setting_value('razorpay_secret')
            );
            $property = Property::findOrFail($propertyId);

            $daysCount = calculateBookingDays($request->check_in, $request->check_out, $property->branch->check_out_time);
            $guestCount = (int) $request->guest_count;
            $baseAmount = $property->base_price * $daysCount;
            $extraCharge = max(0, $guestCount - $property->default_guests) * $property->extra_guest_charge * $daysCount;
            $amount = $baseAmount + $extraCharge;

            $order = $razorpay->order->create([
                'receipt' => 'ROOM_' . time(),
                'amount' => $amount * 100,
                'currency' => 'INR',
            ]);

            $payment = GatewayPayment::create([
                'user_id' => $user->id,
                'property_id' => $propertyId,
                'gateway' => 'razorpay',
                'gateway_order_id' => $order['id'],
                'amount' => $amount,
                'status' => 'pending',
                'request_payload' => json_encode($order),
            ]);
            return response()->json([
                'success' => true,
                'message' => "User registered successfully!",
                'redirect' => false,
                'step_completed' => 1,
                'days_count' => $daysCount,
                'extraCharge' => $extraCharge,
                'base_price' => $baseAmount,
                'amount' => $amount,
                'extra_charge' => $extraCharge,
                'razorpay' => [
                    'key' => setting_value('razorpay_key'),
                    'order_id' => $order['id'],
                    'amount' => $amount * 100,
                    'name' => 'ZuzuStay',
                    'email' => $user->email,
                    'contact' => $user->mobile,
                ]
            ]);
        }
    }

    public function guestBookingInformation(Request $request){
        $propertyId = decrypt($request->property_id);
        $request->merge([
            'property_no' => $propertyId
        ]);
        
        $request->validate([
            'property_no' => 'required|exists:properties,id',
            'guest_count' => 'required|integer|min:1',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            
            'guests' => 'required|array|min:1',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.phone' => 'required|digits:10',
            'guests.*.aadhaar' => 'required|array|min:1',
            'guests.*.aadhaar.*' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        $checkIn  = Carbon::parse($request->check_in)->format('Y-m-d H:i:s');
        $checkOut = Carbon::parse($request->check_out)->format('Y-m-d H:i:s');
        $query = DB::table('offline_bookings')
            ->where('property_id', $propertyId)
            ->whereNull('deleted_at')
            ->where('status', 'active');

        // check if property is booked or not
        $query->where(function ($q) use ($checkIn, $checkOut) {
            $q->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn);
        });
        $exists = $query->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Property already booked in this time range!'
            ],422);
        }

        
        $razorpay = new Razorpay(
            setting_value('razorpay_key'),
            setting_value('razorpay_secret')
        );
        $property = Property::findOrFail($propertyId);


        $daysCount = calculateBookingDays($request->check_in, $request->check_out, $property->branch->check_out_time);
        $guestCount = (int) $request->guest_count;
        $baseAmount = $property->base_price * $daysCount;
        $extraCharge = max(0, $guestCount - $property->default_guests) * $property->extra_guest_charge * $daysCount;
        $amount = $baseAmount + $extraCharge;

        $order = $razorpay->order->create([
            'receipt' => 'ROOM_' . time(),
            'amount' => $amount * 100,
            'currency' => 'INR',
        ]);
        $user = User::where('id', auth()->user()->id)->first();
        $payment = GatewayPayment::create([
            'user_id' => $user->id,
            'property_id' => $propertyId,
            'gateway' => 'razorpay',
            'gateway_order_id' => $order['id'],
            'amount' => $amount,
            'extra_charge' => $extraCharge,
            'status' => 'pending',
            'request_payload' => json_encode($order),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Razorpay order created successfully!",
            'redirect' => false,
            'step_completed' => 1,
            'days_count' => $daysCount,
            'extraCharge' => $extraCharge,
            'base_price' => $baseAmount,
            'total_cost' => $amount,
            'razorpay' => [
                'key' => setting_value('razorpay_key'),
                'order_id' => $order['id'],
                'amount' => $amount * 100,
                'name' => 'ZuzuStay',
                'email' => $user->email,
                'contact' => $user->mobile,
            ]
        ]);
    }
    

    public function verifyPaymentAndBook(Request $request)
    {
        if(empty($request->property_id)){
            return $this->sendError("Property Number is required");
        }
        if(!auth()->check()){
            return $this->sendError("User is not Iogged In");
        }

        $propertyId = decrypt($request->property_id);
        $request->merge([
            'property_no' => $propertyId
        ]);
        
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required|exists:gateway_payments,gateway_order_id',
            'razorpay_signature' => 'required',
            'property_no' => 'required|exists:properties,id',
            'guest_count' => 'required|integer|min:1',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',

            'guests' => 'required|array|min:1',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.phone' => 'required|digits:10',
            'guests.*.aadhaar' => 'required|array|min:1',
            'guests.*.aadhaar.*' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        try {

            $razorpay = new Razorpay(setting_value('razorpay_key'), setting_value('razorpay_secret'));            
            $razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            $payment = GatewayPayment::where('gateway_order_id', $request->razorpay_order_id)->first();
            
            $payment->update([
                'gateway_payment_id' => $request->razorpay_payment_id,
                'gateway_signature' => $request->razorpay_signature,
                'status' => 'paid',
                'paid_at' => now(),
                'response_payload' => json_encode($request->all()),
            ]);

            $payment = GatewayPayment::where('gateway_order_id', $request->razorpay_order_id)->first();

            if($payment->status != 'paid'){
                return $this->sendError("Payment is not done for this booking");
            }
            

            $checkIn  = Carbon::parse($request->check_in)->format('Y-m-d H:i:s');
            $checkOut = Carbon::parse($request->check_out)->format('Y-m-d H:i:s');
            $query = DB::table('offline_bookings')
                ->where('property_id', $propertyId)
                ->whereNull('deleted_at')
                ->where('status', 'active');

            // check if property is booked or not
            $query->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            });
            $exists = $query->exists();
            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Property already booked in this time range!'
                ],422);
            }

            $property = Property::findOrFail($propertyId);
            
            $daysCount = calculateBookingDays($request->check_in, $request->check_out, $property->branch->check_out_time);
            $guestCount = (int) $request->guest_count;
            $baseAmount = $property->base_price * $daysCount;
            $extraCharge = max(0, $guestCount - $property->default_guests) * $property->extra_guest_charge * $daysCount;
            $amount = $baseAmount + $extraCharge;

            // CREATE BOOKING
            $bookingId = OfflineBooking::insertGetId([
                'user_id' => auth()->user()->id,
                'property_id' => $request->property_no,
                'total_guests' => $request->guest_count,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'source' => 'website',
                'payment_mode' => "razorpay",
                'per_day_price' => $baseAmount/$daysCount,
                'booking_days' => $daysCount,
                'total_amount' => $amount,
                'paid_amount' => $amount,
                'extra_guest_charge' => $extraCharge,
                'total_charges' => $extraCharge,
                'transferred_to_owner' => 'yes',
                'added_by' => auth()->user()->id,

            ]);

            $showBookingId = 'BOK' .date('Y') .str_pad($bookingId, 5, '0', STR_PAD_LEFT);
            OfflineBooking::where('id', $bookingId)->update([
                'show_booking_id' => $showBookingId
            ]);

            //GUESTS SAVE
            $existingGuestIds = [];
            foreach ($request->guests as $guest) {
                if (empty($guest['name']) && empty($guest['phone'])) {
                    continue;
                }

                $aadhaarPaths = [];

                if (!empty($guest['aadhaar_old'])) {
                    $aadhaarPaths = explode(',', $guest['aadhaar_old']);
                }

                //multiple files upload
                if (isset($guest['aadhaar']) && is_array($guest['aadhaar'])) {
                    foreach ($guest['aadhaar'] as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $file->move(storage_path('uploads/aadhaar'), $filename);
                            $aadhaarPaths[] = 'uploads/aadhaar/' . $filename;
                        }
                    }
                }

                //final comma separated string
                $aadhaarPathString = !empty($aadhaarPaths) ? implode(',', $aadhaarPaths) : null;

                $newGuest = OfflineBookingGuest::create([
                    'booking_id' => $bookingId,
                    'name' => $guest['name'] ?? null,
                    'phone' => $guest['phone'] ?? null,
                    'aadhaar' => $aadhaarPathString
                ]);

                $existingGuestIds[] = $newGuest->id;
            }

            OfflineBookingGuest::where('booking_id', $bookingId)
                ->whereNotIn('id', $existingGuestIds)
                ->delete();

            //Add Payment Info
            $paymentId = OfflineBookingPayment::insertGetId([
                'booking_id' => $bookingId,
                'paid_amount' => $request->total_cost,
                'payment_mode' => "razorpay",
                'transferred_owner' => 'Yes',
                'payment_gateway_order_id' => $request->razorpay_order_id,
                'payment_date' => now(),
            ]);
            $transactionId = 'INV' .date('Y') .str_pad($paymentId, 5, '0', STR_PAD_LEFT);
            OfflineBookingPayment::where('id', $paymentId)->update([
                'transaction_id' => $transactionId
            ]);
            return response()->json([
                'success' => true,
                'message' => "Property Booked Successfully!",
                'redirect' => true,
                'redirect_url' => url('bookings')
            ]);

        } catch (SignatureVerificationError $e) {

            return response()->json([
                'success' => false,
                'message' => 'Signature verification failed.',
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateFailedPaymentStatus(Request $request){
        $request->validate([
            'razorpay_order_id' => 'required|exists:gateway_payments,gateway_order_id',
            'payment_error' => 'required'
        ]);

        $payment = GatewayPayment::where('gateway_order_id', $request->razorpay_order_id)->update([
            'status' => 'failed',
            'response_payload' => $request->payment_error,
        ]);
    }


    public function contactUs(Request $request){
        return view('frontend.contact-us');
    }
    
}