<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\Additional;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AdditionalController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'name',
                'title' => 'Nama Layanan Tambahan',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Nama Layanan Tambahan',
            ],
            
            [
                'name' => 'price',
                'title' => 'Harga',
                'type' => 'currency',
                'other-attr' => " data-type=currency ",
                'required' => true,
                'placeholder' => 'Harga Layanan Tambahan',
            ],
            [
                'name' => 'type',
                'title' => 'Tipe',
                'type' => 'select',
                'required' => true,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih tipe layanan tambahan',
                 'data' => [
                    [
                        'id' => 'room',
                        'val' => 'Kamar',
                    ],
                    [
                        'id' => 'general',
                        'val' => 'Umum',
                    ],
                ],
            ],
        ],
        "route" => [
            'index' => 'additional.index',
            'add' => 'additional.create',
            'show' => 'additional.show',
            'update' => 'additional.update',
            'edit' => 'additional.edit',
            'store' => 'additional.store',
            'detail' => 'additional.detail',
            'delete' => 'additional.destroy',
        ],
        "tableHead" => ["No", "Nama Layanan Tambahan", "Harga", "Jenis Layanan","aksi"],
        "tableColumns" => ["DT_RowIndex", "name", "price", "type","action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = Additional::get();
            $akses = request()->attributes->get("hakAkses");
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][6]);
                unset($dataPage['tableColumns'][6]);
            }
            $data = (object)[
                'title' => 'Layanan Tambahan',
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
            // return $data;
            return view('pages.additional.index', compact('data'));
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
            'title' => 'Tambah Data Layanan Tambahan',
            'subtitle' => 'Layanan Tambahan',
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
        DB::beginTransaction();
        try {
            $input = [
                'name'=> $request->name,
                'type' =>$request->type,
                'price' =>str_replace('.','',$request->price),
            ];
            Additional::create($input);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data layanan tambahan');
        } catch (\Throwable $th) {
            return $th;
            DB::rollback();
            return back()->with('error', 'Gagal menambah data layanan tambahan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Additional $additional)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Additional $additional)
    {
        try {
            $page = $this->dataPage;
            $dataForm = [];
            $dataForm = $additional->toArray();
            $data = (object) [
                'title' => 'Edit Data Layanan Tambahan',
                'subtitle' => 'Layanan Tambahan',
                'type' => 'edit',
                'action' => route($page['route']['update'], ['additional' => $additional]),
                'data' => $dataForm,
                'forms' => $page['forms'],
            ];
            // return $data;
            return view('template.form', compact('data'));
       } catch (\Throwable $th) {
        throw $th;
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Additional $additional)
    {
        DB::beginTransaction();
        try {
            $input = [
                'name'=> $request->name,
                'type' =>$request->type,
                'price' =>str_replace('.','',$request->price),
            ];
            $additional->update($input);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data layanan tambahan');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah data fasilitas');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Additional $additional)
    {
         try {
            DB::beginTransaction();
            $additional->delete();
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data layanan tambahan');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data layanan tambahan');
        }
    }

    function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn("type", function ($row) {
                return $row->type == 'room' ? 'Kamar' : 'Umum';
            })
            ->addColumn("price", function ($row) {
                return 'Rp. ' . number_format($row->price, 0, ',', '.');
            })
            ->addColumn("action", function ($row) {
                $akses = request()->attributes->get("hakAkses");
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus additional ' . $row->name . ' ?';

                $actionBtn = $akses["access_edit"] != 'Y'? '': '<a href="'. $editRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= $akses["access_delete"] != 'Y'? '': '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}
