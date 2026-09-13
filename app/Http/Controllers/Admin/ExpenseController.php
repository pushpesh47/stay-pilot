<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Branch;
use App\Models\PropertyImage;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Models\City;
use Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Exports\ExpenseListExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
class ExpenseController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('expense.view'), 403, __('User does not have the right permissions.'));

        $data['title'] = 'Expenses';
        $data['create_title'] = 'Expense';

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $branchId =  $request->branch_id;
        $data['branchProperties'] = Property::where('branch_id', $branchId)->get();
        $data['propertyId'] = $propertyId = $request->property_id;
        $keyword = $request->keyword;
        
        if (Auth::user()->isSuperAdmin()) {
            $query = Expense::with(['property.branch.city']);
            $data['branches'] = Branch::with(['city'])
                ->where('status', 'active')
                ->get();
        } else {

            $query = Expense::with(['property.branch.city'])->whereHas('property', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });

            $data['branches'] = Branch::with(['city'])
                ->where('id', Auth::user()->branch_id)
                ->where('status', 'active')
                ->get();
        }
        $query = $query->leftJoin('properties as pr', 'pr.id', '=', 'expenses.property_id')
            ->leftJoin('branches as br', 'br.id', '=', 'pr.branch_id')
            ->leftJoin('cities as c', 'c.id', '=', 'br.city_id')
            ->select(
                'expenses.*',
                'pr.property_number',
                'br.name as branch_name',
                'br.location',
                'br.pincode',
                'c.name as city_name',
                'c.state'
            )
            ->orderBy('id','DESC');

        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('expense_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('expense_date', '=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('expense_date', '=', $toDate);
        }

        if (!empty($branchId) && Auth::user()->isSuperAdmin()) {
            $query->where('pr.branch_id', $branchId);
        }

        if (!empty($propertyId)) {
            $query->where('expenses.property_id', $propertyId);
        }

        // Keyword Filter
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {

                $q->where('expenses.name', 'like', '%' . $keyword . '%')
                    ->orWhere('expenses.amount', 'like', '%' . $keyword . '%')
                    ->orWhere('expenses.received_by', 'like', '%' . $keyword . '%')
                    ->orWhere('expenses.payment_mode', 'like', '%' . $keyword . '%')
                    ->orWhere('expenses.notes', 'like', '%' . $keyword . '%')
                    ->orWhere('pr.property_number', 'like', "%$keyword%")
                    ->orWhere('br.name', 'like', "%$keyword%")
                    ->orWhere('br.location', 'like', "%$keyword%")
                    ->orWhere('br.pincode', 'like', "%$keyword%")
                    ->orWhere('c.name', 'like', "%$keyword%")
                    ->orWhere('c.state', 'like', "%$keyword%");
            });
        }

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('property_number', function ($row) {
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
                ->editColumn('action', function ($expense) {
                    return view('admin.components.admin-action-buttons', [
                        'model' => $expense,
                        'permissions' => [
                            'edit' => 'expense.edit',
                            'delete' => 'expense.delete',
                        ],
                        'routes' => [
                            'edit' =>  'admin.expenses.edit',
                            'delete' => 'admin.expenses.destroy',
                        ],
                        'tableId' => 'expenses-table',
                        'title' => 'expense',
                    ])->render();
                })
                ->editColumn('created_at', function ($expense) {
                    return $expense->created_at->format('d-m-Y');
                })
                ->editColumn('expense_date', function ($row) {
                    return $row->expense_date
                        ? \Carbon\Carbon::parse($row->expense_date)->format('d-m-Y')
                        : '-';
                })
                
                ->rawColumns(['action','created_at'])
                ->make(true);
        }

        return view('admin.expense.list')->with($data);
    }


    public function create()
    {
        $title = 'Add Expense';
        $expense = null;
        if(Auth::user()->isSuperAdmin()){
            $branches = Branch::with(['city'])->where('status','active')->get();
        }
        else{
            $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
        }
        return view('admin.expense.add',  compact('expense','title','branches'));
    }


    public function storeOrUpdate(Request $request)
    {
        $isUpdate = $request->expenseEditId;

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
        ]);

        if ($isUpdate) {

            $branch = Expense::findOrFail($isUpdate);

            $branch->update([
                'name' => $request->name,
                'amount' => $request->amount,
                'property_id' => $request->property_id,
                'received_by' => $request->received_by ?? null,
                'expense_date' => $request->expense_date,
                'payment_mode' => $request->payment_mode ?? null,
                'notes' => $request->notes ?? null,
            ]);
        } else {

            $branch = Expense::create([
                'created_by' => auth()->id(),
                'name' => $request->name,
                'amount' => $request->amount,
                'property_id' => $request->property_id,
                'received_by' => $request->received_by ?? null,
                'expense_date' => $request->expense_date,
                'payment_mode' => $request->payment_mode ?? null,
                'notes' => $request->notes ?? null,

            ]);
        }


        return redirect()->route('admin.expenses.index')
            ->with('success', $isUpdate ? 'Expense updated successfully!' : 'Expense added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        try {
            $title = 'Edit Expense';

            $expenseId = decrypt($id);

            $expense = Expense::findOrFail($expenseId);
            if(Auth::user()->isSuperAdmin()){
                $branches = Branch::with(['city'])->where('status','active')->get();
            }
            else{
                $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
            }

            return view('admin.expense.add', compact('expense', 'title','branches'));
        } catch (\Exception $e) {
            abort(404);
        }
    }


    // Delete branch

    public function destroy($id)
    {
        // Permission check
        if (!auth()->user()->can('expense.delete')) {
            return response()->json([
                'status' => false,
                'message' => 'User does not have permission to delete this expense.',
            ], 403);
        }

        try {
            $expenseId = decrypt($id);
            $expense = Expense::findOrFail($expenseId);
            $expense->delete();

            return response()->json([
                'status' => true,
                'message' => 'Expense deleted successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Expense not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Expense deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete expense. Please try again.',
            ], 500);
        }
    }


    public function exportExpenseExcelList(Request $request)
    {
        $fromDate   = $request->from_date;
        $toDate     = $request->to_date;
        $branchId   = $request->branch_id;
        $propertyId = $request->property_id;
        $keyword    = $request->keyword;
        $type       = '';

        $fileName = 'Expenses';

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

        return Excel::download(new ExpenseListExport(
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

    public function exportExpenseCsvList(Request $request)
    {
        $fromDate   = $request->from_date;
        $toDate     = $request->to_date;
        $branchId   = $request->branch_id;
        $propertyId = $request->property_id;
        $keyword    = $request->keyword;
        $type = 'csv';

        $fileName = 'Expenses List' . '.csv';

        $fileName = 'Expenses';

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

        return Excel::download(new ExpenseListExport(
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
}
