<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Helper\Helpers;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoomsController extends Controller
{
    public $dataPage = [
        'forms' => [
            [
                'name' => 'id_room_type',
                'title' => 'Tipe Kamar',
                'class' => 'select2',
                'type' => 'select',
                'required' => true,
                'placeholder' => 'Pilih Tipe Kamar',
                
            ],
            [
                'name' => 'floor_number',
                'title' => 'Nomor Lantai',
                'type' => 'number',
                'other-attr' => ' min=0 ',
                'class_input' => 'floor_number',
                'required' => true,
                'placeholder' => 'Nomor Lantai',
            ],
            [
                'name' => 'room_number',
                'title' => 'Nomor Kamar',
                'type' => 'number',
                'other-attr' => ' min=0 ',
                'class_input' => 'room_number',
                'required' => true,
                'placeholder' => 'Nomor Kamar',
            ],
            [
                'name' => 'status',
                'title' => 'Status Kamar',
                'type' => 'select',
                'required' => true,
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih Status Kamar',
                'data' => [
                    [
                        'id' => 'Tersedia',
                        'val' => 'Tersedia',
                    ],
                    [
                        'id' => 'Terisi',
                        'val' => 'Terisi',
                    ],
                    [
                        'id' => 'Cleaning',
                        'val' => 'Cleaning',
                    ],
                    [
                        'id' => 'Maintenance',
                        'val' => 'Maintenance',
                    ],
                ],
            ],
            [
                'name' => 'remarks',
                'title' => 'Remarks',
                'type' => 'textarea',
                'placeholder' => 'Remarks',
            ],
        ],
        'route' => [
            'index' => 'room.index',
            'add' => 'room.create',
            'show' => 'room.show',
            'update' => 'room.update',
            'edit' => 'room.edit',
            'store' => 'room.store',
            'detail' => 'room.detail',
            'delete' => 'room.destroy',
        ],
        'tableHead' => ['No', 'Tipe Kamar', 'Nomor Kamar', 'Nomor Lantai', 'Status', 'Remarks', 'aksi'],
        'tableColumns' => ['DT_RowIndex', 'type_name', 'room_number', 'floor_number', 'status', 'remarks', 'action'],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = Rooms::with('roomType')->get();
            
            $akses = request()->attributes->get('hakAkses');
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][6]);
                unset($dataPage['tableColumns'][6]);
            }
            $data = (object) [
                'title' => 'Data Kamar',
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
            return view('pages.rooms.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formData = $this->dataPage['forms'];
        $formData[0]['data'] = RoomTypes::get()->map(function ($item) {
            return [
                'id' => $item->id,
                'val' => $item->type_name,
            ];
        });
        
        $data = (object) [
            'title' => 'Data Kamar',
            'subtitle' => 'Tambah Kamar',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'action' => route($this->dataPage['route']['store']),
            'data' => [],
            'forms' => $formData,
            'others_data' => (object) [
                'max_room_number' => (Rooms::max('room_number') ?? 0) + 1,
            ],
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
                'id_room_type' => $request->id_room_type,
                'floor_number' => $request->floor_number,
                'room_number' => $request->room_number,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ];
            $room = Rooms::create($input);
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data kamar');
        } catch (\Throwable $th) {
            return $th;
            DB::rollback();

            return back()->with('error', 'Gagal menambah data kamar');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rooms $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rooms $room)
    {
        $formData = $this->dataPage['forms'];
        $formData[0]['data'] = RoomTypes::get()->map(function ($item) {
            return [
                'id' => $item->id,
                'val' => $item->type_name,
            ];
        });
        
        $data = (object) [
            'title' => 'Data Kamar',
            'subtitle' => 'Tambah Kamar',
            'base_title' => 'Tambah Data',
            'type' => 'edit',
            'action' => route($this->dataPage['route']['update'], ['room' => $room]),
            'data' => $room,
            'forms' => $formData,
        ];

        return view('template.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rooms $room)
    {
        
        DB::beginTransaction();
        try {
            $input = [
                'id_room_type' => $request->id_room_type,
                'floor_number' => $request->floor_number,
                'room_number' => $request->room_number,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ];
            $room = Rooms::where('id', $room->id)->update($input);
            
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil mengubah data kamar');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', 'Gagal mengubah data kamar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rooms $room)
    {
         try {
            DB::beginTransaction();
            $room->delete();
            DB::commit();

            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data kamar');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', 'Gagal menghapus data kamar');
        }
    }

    public function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('type_name', function ($row) {
                return $row->roomType->type_name ?? '-';
            })
            ->addColumn('status', function ($row) {
                $html = '';
                switch ($row->status) {
                    case 'Tersedia':
                        $html = '<span class="badge bg-success">'.$row->status.'</span>';
                        break;
                    case 'Terisi':
                        $html = '<span class="badge bg-warning">'.$row->status.'</span>';
                        break;
                    case 'Cleaning':
                        $html = '<span class="badge bg-info">'.$row->status.'</span>';
                        break;
                    case 'Maintenance':
                        $html = '<span class="badge bg-secondary">'.$row->status.'</span>';
                        break;
                }
                return $html;
            })
            ->addColumn('action', function ($row) {
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus data kamar '.$row->room_number.'-'.$row->roomType->type_name.' ?';

                $actionBtn = $akses['access_edit'] != 'Y' ? '' : '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function generateRoomNumber($floor) {
        $maxNumber = Rooms::where('floor_number', $floor)->max('room_number') ?? 0;
        
        return response()->json([
            'status' => true,
            'data' => $maxNumber,
        ]);
    }
}
