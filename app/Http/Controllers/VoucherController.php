<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Helper\Helpers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'code',
                'title' => 'Kode Voucher',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Kode Voucher',
            ],
            [
                'name' => 'name',
                'title' => 'Nama Voucher',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Nama Voucher',
            ],
            [
                'name' => 'type',
                'title' => 'Tipe',
                'type' => 'select',
                'required' => true,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih tipe diskon',
                 'data' => [
                    [
                        'id' => 'percentage',
                        'val' => 'Persentase',
                    ],
                    [
                        'id' => 'fixed',
                        'val' => 'Nominal',
                    ],
                ],
            ],
            [
                'name' => 'value',
                'title' => 'Nilai Diskon',
                'type' => 'number',
                'required' => true,
                'placeholder' => 'Nilai Diskon',
            ],
            [
                'name' => 'start_date',
                'title' => 'Tanggal Mulai Berlaku',
                'type' => 'date',
                'required' => true,
                'placeholder' => 'Pilih Tanggal',
            ],
            [
                'name' => 'end_date',
                'title' => 'Tanggal Berakhir Berlaku',
                'type' => 'date',
                'required' => true,
                'placeholder' => 'Pilih Tanggal',
            ],
            [
                'name' => 'usage_limit',
                'title' => 'Jumlah Pemakaian',
                'type' => 'number',
                'required' => false,
                'placeholder' => 'Jumlah Pemakaian',
            ],
            [
                'name' => 'max_discount',
                'title' => 'Diskon Maksimum',
                'type' => 'number',
                'required' => false,
                'placeholder' => 'Diskon Maksimum',
            ],
            [
                'name' => 'status',
                'title' => 'Status',
                'type' => 'select',
                'required' => false,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih Status',
                'data' => [
                    [
                        'id' => 'Active',
                        'val' => 'Aktif',
                    ],
                    [
                        'id' => 'Inactive',
                        'val' => 'Tidak Aktif',
                    ],
                ],
            ],
            [
                'name' => 'description',
                'title' => 'Deskripsi',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Deskripsi',
            ],
        ],
        "route" => [
            'index' => 'voucher.index',
            'add' => 'voucher.create',
            'show' => 'voucher.show',
            'update' => 'voucher.update',
            'edit' => 'voucher.edit',
            'store' => 'voucher.store',
            'detail' => 'voucher.detail',
            'delete' => 'voucher.destroy',
        ],
        "tableHead" => ["No", "Kode", "Nama", "Tipe", "Nilai Diskon","Tanggal Mulai Berlaku","Tanggal Berakhir Berlaku","Jumlah Pemakaian","Diskon Maksimum","Status","aksi"],
        "tableColumns" => ["DT_RowIndex", "code", "name", "type", "value","start_date","end_date","usage_limit","max_discount","status","action"],
    ];
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = Voucher::get();
            $akses = request()->attributes->get("hakAkses");
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][count($dataPage['tableHead']) - 1]);
                unset($dataPage['tableColumns'][count($dataPage['tableColumns']) - 1]);
            }
            $data = (object) [
                'title' => 'Voucher',
                "createBtn" => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                "routeAdd" => route($dataPage['route']['add']),
                "routeData" => route($dataPage['route']['index']),
                'data' => $list,
            ];
            if (request()->ajax()) {
                return $this->ajax($list);
            }
            return view('pages.voucher.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forms = $this->dataPage['forms'];
        $data = (object) [
            'title' => 'Tambah Data Voucher',
            'subtitle' => 'Data Voucher',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'action' => route($this->dataPage['route']['store']),
            'data' => [],
            'forms' => $forms,
        ];
        return view('template.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $input = [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'type' => $request->type,
                'value' => $request->value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'usage_limit' => $request->usage_limit,
                'max_discount' => $request->max_discount,
                'status' => $request->status,
                'description' => $request->description,
            ];

            $result = Voucher::create($input);
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Data Voucher berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', "Terjadi kesalahan: " . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        $data = (object) [
            'title' => 'Detail Data Voucher',
            'subtitle' => 'Data Voucher',
            'base_title' => 'Detail Data',
            'type' => 'show',
            'data' => $voucher,
            'forms' => $this->dataPage['forms'],
        ];
        return view('template.form', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        $forms = $this->dataPage['forms'];
        
        $data = (object) [
            'title' => 'Ubah Data Voucher',
            'subtitle' => 'Data Voucher',
            'base_title' => 'Ubah Data',
            'type' => 'edit',
            'action' => route($this->dataPage['route']['update'], $voucher->id),
            'data' => $voucher,
            'forms' => $forms,
        ];
        return view('template.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        try {
            DB::beginTransaction();
            
            $input = [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'type' => $request->type,
                'value' => $request->value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'usage_limit' => $request->usage_limit,
                'max_discount' => $request->max_discount,
                'status' => $request->status,
                'description' => $request->description,
            ];

            $result = $voucher->update($input);
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Data Voucher berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', "Terjadi kesalahan: " . $th->getMessage());
            
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        try {
            DB::beginTransaction();

            $voucher->delete();
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data voucher');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', "Terjadi kesalahan: " . $th->getMessage());
        }
    }

    public function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            
            ->addColumn('action', function ($row) {
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus voucher '.$row->name.' ?';

                $actionBtn = $akses['access_edit'] != 'Y' ? '' : '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
