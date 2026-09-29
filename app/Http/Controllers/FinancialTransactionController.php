<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FinancialTransactionController extends Controller
{
    public $dataPage = [

        'route' => [
            'index' => 'cashflow.index',
            'add' => 'cashflow.create',
            'show' => 'cashflow.show',
            'update' => 'cashflow.update',
            'edit' => 'cashflow.edit',
            'store' => 'cashflow.store',
            'detail' => 'cashflow.detail',
            'delete' => 'cashflow.destroy',
        ],
        'tableHead' => ['No', 'Tanggal Transaksi', 'Jenis Transaksi', 'Kategori', 'Nominal', 'Catatan', 'Petugas', 'aksi'],
        'tableColumns' => ['DT_RowIndex', 'transaction_date', 'transaction_type', 'account_name', 'amount', 'description', 'created_by', 'action'],
    ];

    public function index(Request $request)
    {
        try {
            $dataPage = $this->dataPage;
            $list = FinancialTransaction::with([
                'account' => function ($q) {
                    $q->select('id', 'account_code', 'account_name', 'type', 'balance')->get();
                },
                'user']);
            $category = FinancialAccount::whereIn('id', [17, 18, 19, 20, 29, 21, 22, 25, 26, 3, 8, 11, 12, 13, 14])->get();
            $summary = FinancialTransaction::select(
                DB::raw('SUM(CASE WHEN transaction_type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN transaction_type = "expense" THEN amount ELSE 0 END) as expense'));

            $akses = request()->attributes->get('hakAkses');
            if (isset($request->start_date) && isset($request->end_date)) {
                $list = $list->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
                $summary = $summary->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
            }
            if (isset($request->type_transaction)) {
                $list = $list->where('transaction_type', $request->type_transaction);
            }
            $list = $list->orderBy('transaction_date', 'DESC')->get();
            $summary = $summary->first();

            $data = (object) [
                'title' => 'Cash Flow',
                'createBtn' => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                'routeAdd' => route($dataPage['route']['add']),
                'routeData' => route($dataPage['route']['index']),
                'data' => $list,
                'summary' => $summary,
                'category' => $category,
            ];

            if (isset($request->filter)) {
                return (object) ['summary' => $summary];
            }

            if (request()->ajax()) {
                return $this->ajax($list);
            }

            // return $data;
            return view('pages.cashflow.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'transaction_date' => 'required',
                'transaction_category' => 'required',
                'transaction_type' => 'required',
                'amount' => 'required',
                'description' => 'nullable',
            ]);
            FinancialTransaction::create([
                'transaction_date' => $request->transaction_date,
                'financial_account_id' => $request->transaction_category,
                'transaction_type' => $request->transaction_type,
                'amount' => $request->amount,
                'reference_type' => in_array($request->transaction_category, [3, 11, 12]) ? 'booking' : 'general',
                'description' => $request->description ?? '-',
                'created_by' => Auth::user()->id,
            ]);
            DB::commit();

            return redirect()->route($this->dataPage['route']['index'])
                ->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route($this->dataPage['route']['index'])
                ->with('error', 'Data gagal ditambahkan, message : '.$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialTransaction $cashflow)
    {

        $data = FinancialTransaction::with([
            'account' => function ($q) {
                $q->select('id', 'account_code', 'account_name', 'type', 'balance')->get();
            },
            'user'])->where('id', $cashflow->id)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialTransaction $financialTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialTransaction $cashflow)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'transaction_date' => 'required',
                'transaction_category' => 'required',
                'transaction_type' => 'required',
                'amount' => 'required',
                'description' => 'nullable',
            ]);

            $cashflow->update([
                'transaction_date' => $request->transaction_date,
                'financial_account_id' => $request->transaction_category,
                'transaction_type' => $request->transaction_type,
                'amount' => $request->amount,
                'reference_type' => in_array($request->transaction_category, [3, 11, 12]) ? 'booking' : 'general',
                'description' => $request->description ?? '-',
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB::commit();
            return redirect()->route($this->dataPage['route']['index'])
                ->with('success', 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->insertLog('error cashflow', $th);

            return redirect()->route($this->dataPage['route']['index'])
                ->with('error', 'Data gagal diupdate, message : '.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialTransaction $cashflow)
    {
        DB::beginTransaction();
        try {
            $cashflow->delete();
            DB::commit();

           return redirect()->route($this->dataPage['route']['index'])
                ->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->insertLog('error cashflow', $th);

            return redirect()->route($this->dataPage['route']['index'])
                ->with('error', 'Data gagal dihapus, message : '.$th->getMessage());
        }
    }

    public function ajax($list)
    {

        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('transaction_date', function ($row) {
                return Helpers::tanggal($row->transaction_date);
            })
            ->addColumn('amount', function ($row) {
                return Helpers::rupiah($row->amount);
            })
            ->addColumn('account_name', function ($row) {
                return $row->account->account_code == '1-100' ? 'Reservasi' : $row->account->account_name;
            })
            ->addColumn('created_by', function ($row) {
                return $row->user->name;
            })
            ->addColumn('transaction_type', function ($row) {
                return $row->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran';
            })
            ->addColumn('action', function ($row) {
                $category = $row->account->account_code == '1-100' ? 'Reservasi' : $row->account->account_name;
                $type = $row->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran';
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus transaksi '.$type.'-'.$category.' ?';
                $showDelete = false;
                $showEdit = false;
                if ($row->reference_type == 'booking') {
                    $showDelete = false;
                    $showEdit = false;
                } else {
                    if ($akses['access_delete'] == 'Y') {
                        $showDelete = true;
                    }
                    if ($akses['access_edit'] == 'Y') {
                        $showEdit = true;
                    }

                }
                $actionBtn = $showEdit == false ? '' : '<button class="btn-sm me-2 btn modal-effect btn-warning" data-bs-toggle="modal" data-bs-effect="effect-scale" style="font-size:12px;" onclick="editData(\''.$detailRoute.'\',\''.$row->id.'\')" href="#modal-edit"><span class="fe fe-edit"></span></button>';
                $actionBtn .= $showDelete == false ? '' : '<button class="btn-sm mr-2 modal-effect btn btn-danger" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:12px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
