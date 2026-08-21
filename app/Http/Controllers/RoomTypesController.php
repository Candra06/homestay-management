<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\Facility;
use App\Models\RoomFacilities;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoomTypesController extends Controller
{
    public $dataPage = [
        'forms' => [
            [
                'name' => 'type_name',
                'title' => 'Tipe Kamar',
                'type' => 'text',
                'required' => true,

                'placeholder' => 'Tipe Kamar',
            ],

            [
                'name' => 'base_price',
                'title' => 'Harga',
                'type' => 'currency',
                'other-attr' => ' data-type=currency ',
                'required' => true,
                'placeholder' => 'Harga per malam',
            ],
            [
                'name' => 'kapasitas',
                'title' => 'Kapasitas Tamu',
                'type' => 'number',
                'other-attr' => ' min=0 ',
                'required' => true,
                'placeholder' => 'Kapasitas Tamu',
            ],
            [
                'name' => 'bed_type',
                'title' => 'Jenis Bed',
                'type' => 'select',
                'required' => true,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih tipe kasur',
                'data' => [
                    [
                        'id' => 'king',
                        'val' => 'Single (1)',
                    ],
                    [
                        'id' => 'twin',
                        'val' => 'Double (2)',
                    ],
                ],
            ],
        ],
        'route' => [
            'index' => 'room-type.index',
            'add' => 'room-type.create',
            'show' => 'room-type.show',
            'update' => 'room-type.update',
            'edit' => 'room-type.edit',
            'store' => 'room-type.store',
            'detail' => 'room-type.detail',
            'delete' => 'room-type.destroy',
        ],
        'tableHead' => ['No', 'Tipe Kamar', 'Kapasitas', 'Jenis Bed', 'Harga', 'aksi'],
        'tableColumns' => ['DT_RowIndex', 'type_name', 'kapasitas', 'bed_type', 'base_price', 'action'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = RoomTypes::get();
            $akses = request()->attributes->get('hakAkses');
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][6]);
                unset($dataPage['tableColumns'][6]);
            }
            $data = (object) [
                'title' => 'Tipe Kamar',
                'createBtn' => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                'routeAdd' => route($dataPage['route']['add']),
                'routeData' => route($dataPage['route']['index']),
                'data' => $list,
            ];

            if (request()->ajax()) {
                return $this->ajax($list);
            }

            // return $data;
            return view('pages.room-types.index', compact('data'));
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
        $facility = Facility::where('type', 'room')->get();

        foreach ($facility as $f) {
            $forms[] = [
                'name' => 'facility[]',
                'title' => $f->nama_fasilitas,
                'type' => 'checkbox',
                'required' => false,
                'custom-class-wrapper' => 'col-md-2 col-4',
                'value' => $f->id,
                'placeholder' => '',
            ];
        }
        $data = (object) [
            'title' => 'Tambah Data Tipe Kamar',
            'subtitle' => 'Tipe Kamar',
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
                'type_name' => $request->type_name,
                'kapasitas' => $request->kapasitas,
                'bed_type' => $request->bed_type,
                'base_price' => str_replace('.', '', $request->base_price),
            ];
            $room = RoomTypes::create($input);
            foreach ($request->facility as $key => $value) {
                RoomFacilities::create([
                    'id_room' => $room->id,
                    'id_facility' => $value,
                ]);
            }
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
    public function show(RoomTypes $room_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomTypes $room_type)
    {
        try {

            $page = $this->dataPage;
            $dataForm = [];
            $dataForm = $room_type->toArray();
            $forms = $page['forms'];
            $facility = Facility::where('type', 'room')->get();
            // return $room_type->facilities;
            foreach ($facility as $f) {

                $forms[] = [
                    'name' => 'facility[]',
                    'title' => $f->nama_fasilitas,
                    'type' => 'checkbox',
                    'required' => false,
                    'custom-class-wrapper' => 'col-md-2 col-4',
                    'other-attr' => in_array($f->id, $room_type->facilities->pluck('id_facility')->toArray()) ? 'checked' : '',
                    'value' => $f->id,
                    'placeholder' => '',
                ];
            }
            
            $data = (object) [
                'title' => 'Edit Data Tipe Kamar',
                'subtitle' => 'Tipe Kamar',
                'type' => 'edit',
                'action' => route($page['route']['update'], ['room_type' => $room_type]),
                'data' => $dataForm,
                'forms' => $forms,
            ];

            return view('template.form', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomTypes $room_type)
    {
        DB::beginTransaction();
        // return $request;
        try {
            $input = [
                'type_name' => $request->type_name,
                'kapasitas' => $request->kapasitas,
                'bed_type' => $request->bed_type,
                'base_price' => str_replace('.', '', $request->base_price),
            ];
            RoomTypes::where('id', $room_type->id)->update($input);
            RoomFacilities::where('id_room', $room_type->id)->delete();
            foreach ($request->facility as $key => $value) {
                RoomFacilities::create([
                    'id_room' => $room_type->id,
                    'id_facility' => $value,
                ]);
            }   
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data layanan tambahan');
        } catch (\Throwable $th) {
            return $th;
            DB::rollback();

            return back()->with('error', 'Gagal menambah data tipe kamar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomTypes $room_type)
    {
        try {
            DB::beginTransaction();
            $room_type->delete();
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data tipe kamar');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', 'Gagal menghapus data tipe kamar');
        }
    }

    public function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('bed_type', function ($row) {
                return $row->bed_type == 'king' ? 'King (1 kasur)' : 'Twin (2 kasur)';
            })
            ->addColumn('base_price', function ($row) {
                return 'Rp. '.number_format($row->base_price, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus tipe kamar '.$row->type_name.' ?';

                $actionBtn = $akses['access_edit'] != 'Y' ? '' : '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
