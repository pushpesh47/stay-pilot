<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OfflineBookingListExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $fromDate;
    protected $toDate;
    protected $branchId;
    protected $propertyId;
    protected $keyword;
    protected $type;

public function __construct($fromDate = null, $toDate = null, $branchId = null, $propertyId = null, $keyword = null, $type = null) {
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
    $this->branchId = $branchId;
    $this->propertyId = $propertyId;
    $this->keyword = $keyword;
    $this->type = $type;
}

    public function collection(): Collection
    {
        $query = DB::table('offline_bookings as b')
            ->leftJoin('properties as pr', 'pr.id', '=', 'b.property_id')
            ->leftJoin('branches as br', 'br.id', '=', 'pr.branch_id')
            ->leftJoin('cities as c', 'c.id', '=', 'br.city_id')
            ->select(
                'b.id',
                'b.show_booking_id',
                'b.check_in',
                'b.check_out',
                'b.total_amount',
                'b.paid_amount',
                'pr.property_number',
                'br.name as branch_name',
                'br.location',
                'br.pincode',
                'c.name as city_name',
                'c.state',

                'b.total_guests',
                'b.source',
                'b.payment_mode',
                'b.cash_received_by',
                'b.per_day_price',

                'b.booking_days',
                'b.transferred_to_owner',
                'b.early_checkin_charges',
                'b.late_checkout_charges',
                'b.damage_charges',
                'b.total_charges',
                'b.created_at',
                'b.by_refernece',
                DB::raw('GROUP_CONCAT(DISTINCT  
    CONCAT(
        g.name,
        IF(g.phone IS NOT NULL AND g.phone != "", CONCAT(" (", g.phone, ")"), ""),
        " | Id=",
        CASE 
            WHEN g.aadhaar IS NULL OR g.aadhaar = "" THEN "No"
            ELSE "Yes"
        END
    ) 
SEPARATOR "\n") as guests'),
                DB::raw('GROUP_CONCAT(DISTINCT 
    CONCAT(
        p.paid_amount,
        " (",
        DATE_FORMAT(p.payment_date, "%d/%m/%Y"),
        ")",
        " | Mode- ", p.payment_mode,
        " | Transferred Owner- ",
        CASE 
            WHEN p.transferred_owner = "Transferred Owner" THEN "Yes"
            ELSE "No"
        END,
        " | Screenshot- ",
        CASE 
            WHEN p.payment_screenshot IS NULL OR p.payment_screenshot = "" THEN "No"
            ELSE "Yes"
        END,
        CASE 
            WHEN p.payment_mode = "cash" 
            THEN CONCAT(" | Received By- ", IFNULL(p.receivedBy, "N/A"))
            ELSE ""
        END
    )
SEPARATOR "\n") as payments'),

                DB::raw('GROUP_CONCAT(DISTINCT 
    CONCAT(
        DATE_FORMAT(e.old_checkout, "%d/%m/%Y"),
        " → ",
        DATE_FORMAT(e.new_checkout, "%d/%m/%Y"),
        " (",
        e.extra_days, " Day",
        IF(e.extra_days > 1, "s", ""),
        ", ₹",
        e.extra_amount,
        ")"
    )
SEPARATOR "\n") as extensions')


            )



            ->leftJoin('offline_booking_guests as g', 'g.booking_id', '=', 'b.id')
            ->leftJoin('offline_booking_payments as p', 'p.booking_id', '=', 'b.id')
            ->leftJoin('offline_booking_extensions as e', 'e.booking_id', '=', 'b.id');
            

        if (!auth()->user()->isSuperAdmin()) {
            $query->where('pr.branch_id', auth()->user()->branch_id);
        }

        if (!empty($this->branchId)) {
            $query->where('pr.branch_id', $this->branchId);
        }

        if (!empty($this->propertyId)) {
            $query->where('b.property_id', $this->propertyId);
        }

        $query->whereNull('b.deleted_at')
            ->groupBy('b.id')
            ->orderBy('b.id', 'DESC');


        if ($this->fromDate && $this->toDate) {

            $query->where(function ($q) {
                $form = date('Y-m-d', strtotime($this->fromDate));
                $to = date('Y-m-d', strtotime($this->toDate));

                $q->whereDate('b.check_in', '>=', $form)
                    ->whereDate('b.check_out', '<=', $to);
                    
            });
        } elseif ($this->fromDate) {
            $form = date('Y-m-d', strtotime($this->fromDate));
            $query->whereDate('b.check_in', '=', $form);
        } elseif ($this->toDate) {

            $to = date('Y-m-d', strtotime($this->toDate));
            $query->whereDate('b.check_out', '=', $to);
        }


        if ($this->keyword) {

            $search = "%{$this->keyword}%";

            $query->where(function ($q) use ($search) {

                $q->where('b.show_booking_id', 'like', $search)
                    ->orWhere('br.name', 'like', $search)
                    ->orWhere('pr.property_number', 'like', $search)
                    ->orWhere('c.name', 'like', $search)
                    ->orWhere('c.state', 'like', $search)
                    ->orWhere('b.total_guests', 'like', $search)
                    ->orWhere('b.total_amount', 'like', $search)
                    ->orWhere('b.paid_amount', 'like', $search)
                    ->orWhere('b.source', 'like', $search)
                    ->orWhere('b.payment_mode', 'like', $search)
                    ->orWhere('b.cash_received_by', 'like', $search)
                    ->orWhere('b.per_day_price', 'like', $search)
                    ->orWhere('b.booking_days', 'like', $search)
                    ->orWhere('b.transferred_to_owner', 'like', $search)
                    ->orWhere('b.early_checkin_charges', 'like', $search)
                    ->orWhere('b.late_checkout_charges', 'like', $search)
                    ->orWhere('b.damage_charges', 'like', $search)
                    ->orWhere('b.total_charges', 'like', $search)
                    ->orWhere('b.by_refernece', 'like', $search)
                    ->orWhere('br.name', 'like', $search)
                    ->orWhere('br.location', 'like', $search)
                    ->orWhere('br.pincode', 'like', $search)
                    ->orWhere('c.name', 'like', $search)
                    ->orWhere('c.state', 'like', $search)
                    

                    ->orWhereExists(function ($sub) use ($search) {
                        $sub->select(DB::raw(1))
                            ->from('offline_booking_guests as g2')
                            ->whereColumn('g2.booking_id', 'b.id')
                            ->where(function ($q2) use ($search) {
                                $q2->where('g2.name', 'like', $search)
                                    ->orWhere('g2.phone', 'like', $search);
                            });
                    })

                    ->orWhereExists(function ($sub) use ($search) {
                        $sub->select(DB::raw(1))
                            ->from('offline_booking_payments as p2')
                            ->whereColumn('p2.booking_id', 'b.id')
                            ->where(function ($q3) use ($search) {
                                $q3->where('p2.transaction_id', 'like', $search)
                                    ->orWhere('p2.paid_amount', 'like', $search);
                            });
                    });
            });
        }

        return $query->get();
    }

    public function map($row): array
    {
        return [

            $row->created_at
                ? Carbon::parse($row->created_at)->format('d/m/Y')
                : '',

            $row->show_booking_id ?? $row->id,

            $row->property_number ?? '',
            $row->total_guests ?? '',


            $row->check_in
                ? Carbon::parse($row->check_in)->format('d/m/Y')
                : '',

            $row->check_out
                ? Carbon::parse($row->check_out)->format('d/m/Y')
                : '',





            $row->per_day_price,
            $row->booking_days,
            $row->total_amount,
            $row->paid_amount,
            $row->payment_mode,
            $row->by_refernece,
            $row->source,
            $row->transferred_to_owner,
            $row->guests ?? '',
            $row->payments ?? '',
            $row->early_checkin_charges,
            $row->late_checkout_charges,
            $row->damage_charges,
            $row->total_charges,
            $row->extensions,

        ];
    }
    public function headings(): array
    {
        return [
            'Date',
            'Booking ID',
            'Property',
            'Total Guests',
            'Check In',
            'Check Out',
            'Rev per day',
            'Booking Days',
            'Booking Amount',
            'Total Paid Amount',
            'Payment Mode',
            'By Refernece',
            'Source',
            'Transferred to Owner',
            'Guests',
            'Payments',
            'Early Check-in Charges',
            'Late Checkout Charges',
            'Damage Charges',
            'Total Charges',
            'Extended',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 35,
            'G' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);
        $sheet->getStyle('P')->getAlignment()->setWrapText(true);
        $sheet->getStyle('U')->getAlignment()->setWrapText(true);
    }
}
