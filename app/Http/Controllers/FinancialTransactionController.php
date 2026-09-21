<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class FinancialTransactionController extends Controller
{
    public $dataPage = [
       
        "route" => [
            'index' => 'cashflow.index',
            'add' => 'cashflow.create',
            'show' => 'cashflow.show',
            'update' => 'cashflow.update',
            'edit' => 'cashflow.edit',
            'store' => 'cashflow.store',
            'detail' => 'cashflow.detail',
            'delete' => 'cashflow.destroy',
        ],
        "tableHead" => ["No","Tanggal Transaksi", "Jenis Transaksi", "Kategori","Nominal", "Catatan", "Petugas","aksi"],
        "tableColumns" => ["DT_RowIndex", "transaction_date", "transaction_type", "account_name", "amount","description","created_by","action"],
    ];
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = FinancialTransaction::with([
            'account' => function ($q)  {
                $q->select('id', 'account_code', 'account_name', 'type', 'balance')->get();
            },
            'user'])
            ->orderBy('transaction_date', 'DESC')
            ->get();

            $summary = FinancialTransaction::select(
                DB::raw('SUM(CASE WHEN transaction_type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN transaction_type = "expense" THEN amount ELSE 0 END) as expense')
            )->first();
            
            $akses = request()->attributes->get('hakAkses');
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][7]);
                unset($dataPage['tableColumns'][7]);
            }
            $data = (object) [
                'title' => 'Cash Flow',
                'createBtn' => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                'routeAdd' => route($dataPage['route']['add']),
                'routeData' => route($dataPage['route']['index']),
                'data' => $list,
                'summary' => $summary,
            ];

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialTransaction $financialTransaction)
    {
        //
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
    public function update(Request $request, FinancialTransaction $financialTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialTransaction $financialTransaction)
    {
        //
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
                return $row->account->account_code == '1-100'? 'Reservasi':$row->account->account_name;
            })
            ->addColumn('created_by', function ($row) {
                return $row->user->name;
            })
            ->addColumn('transaction_type', function ($row) {
                return $row->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran';
            })
            ->addColumn('action', function ($row) {
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus tipe kamar '.$row->type_name.' ?';

                $actionBtn = $akses['access_edit'] != 'Y' ? '' : '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn btn-warning" style="font-size:12px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn btn-danger" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:12px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
