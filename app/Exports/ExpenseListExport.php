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

class ExpenseListExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
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
        $query = DB::table('expenses as e')
            ->leftJoin('properties as pr', 'pr.id', '=', 'e.property_id')
            ->leftJoin('branches as br', 'br.id', '=', 'pr.branch_id')
            ->leftJoin('cities as c', 'c.id', '=', 'br.city_id')
            ->select(
                'e.*',
                'pr.property_number',
                'br.name as branch_name',
                'br.location',
                'br.pincode',
                'c.name as city_name',
                'c.state'
            );
            
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('pr.branch_id', auth()->user()->branch_id);
        }

        if (!empty($this->branchId)) {
            $query->where('pr.branch_id', $this->branchId);
        }

        if (!empty($this->propertyId)) {
            $query->where('e.property_id', $this->propertyId);
        }

        $query->whereNull('e.deleted_at')
            ->orderBy('e.id', 'DESC');


        if ($this->fromDate && $this->toDate) {

            $query->where(function ($q) {
                $form = date('Y-m-d', strtotime($this->fromDate));
                $to = date('Y-m-d', strtotime($this->toDate));

                $q->whereDate('e.expense_date', '>=', $form)
                    ->whereDate('e.expense_date', '<=', $to);
                    
            });
        } elseif ($this->fromDate) {
            $form = date('Y-m-d', strtotime($this->fromDate));
            $query->whereDate('e.expense_date', '=', $form);
        } elseif ($this->toDate) {

            $to = date('Y-m-d', strtotime($this->toDate));
            $query->whereDate('e.expense_date', '=', $to);
        }

         if (!empty($this->keyword)) {
            $keyword = $this->keyword;
            $query->where(function ($q) use ($keyword) {               
                $q->where('e.name', 'like', "%{$keyword}%")
                    ->orWhere('e.amount', 'like', "%{$keyword}%")
                    ->orWhere('e.received_by', 'like', "%{$keyword}%")
                    ->orWhere('e.payment_mode', 'like', "%{$keyword}%")
                    ->orWhere('e.notes', 'like', "%{$keyword}%")
                    ->orWhere('br.name', 'like', "%{$keyword}%")
                    ->orWhere('br.location', 'like', "%{$keyword}%")
                    ->orWhere('br.pincode', 'like', "%{$keyword}%")
                    ->orWhere('pr.property_number', 'like', "%{$keyword}%")
                    ->orWhere('c.name', 'like', "%{$keyword}%")
                    ->orWhere('c.state', 'like', "%{$keyword}%");
            });
        }

        return $query->get();
    }

    public function map($row): array
    {
        return [
            
            $row->name,
            $row->amount,
            $row->property_number,
            $row->received_by,

            $row->expense_date
                ? Carbon::parse($row->expense_date)->format('d/m/Y')
                : '',

            $row->payment_mode,
            $row->notes,


            $row->created_at
                ? Carbon::parse($row->created_at)->format('d/m/Y H:i')
                : '',

        ];
    }

    public function headings(): array
    {
        return [           
            'Name',
            'Amount',
            'Property',
            'Received By',
            'Expense Date',
            'Payment Mode',
            'Notes',
            'Created At',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 40,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
    }
}
