<?php

namespace App\Http\Controllers\Guest;

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
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function bookings()
    {
        $userId = auth()->user()->id;
        $data['bookings'] = OfflineBooking::with(['booker','property.images','property.branch.city','guests','extensions'])
            ->where('user_id',$userId)
            ->orderBy('id',"DESC")->get();
        // echo "<pre>"; print_r($data['bookings']->toArray());exit;
        return view('frontend.guest.bookings', $data);
    }

    public function generateInvoice(Request $request, $bookingId)
    {
        // We'll fetch the booking later
        $bookingId = decrypt($bookingId);
        $booking = OfflineBooking::with(['booker','property.branch.city','guests','extensions'])
            ->where('id',$bookingId)->first();
        // echo "<pre>"; print_r($booking->toArray());exit;
        $pdf = Pdf::loadView('invoices.invoice', [
            'booking' => $booking,
        ]);
        // return $pdf->stream($booking->show_booking_id .'.pdf');
        return $pdf->download($booking->show_booking_id .'.pdf');
    }
}