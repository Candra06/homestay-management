<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Helpers;

class GuestController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'nama_lengkap',
                'title' => 'Nama',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Masukkan Nama Lengkap',
            ],
            [
                'name' => 'jabatan',
                'title' => 'Jabatan',
                'type' => 'select',
                'required' => true,
                'placeholder' => 'Pilih Jabatan',
                'class' => 'select2-no-search',
                'data' => [
                    ['id' => 'Manager', 'val' => 'Manager'],
                    ['id' => 'SPV', 'val' => 'SPV'],
                    ['id' => 'Front Office', 'val' => 'Front Office'],
                    ['id' => 'Staff', 'val' => 'Staff'],
                ],
            ],
            [
                'name' => 'status',
                'title' => 'Status',
                'type' => 'select',
                'required' => true,
                'placeholder' => 'Pilih Status',
                'class' => 'select2-no-search',
                'data' => [
                    ['id' => 'aktif', 'val' => 'Aktif'],
                    ['id' => 'non-aktif', 'val' => 'Tidak Aktif'],
                ],
            ],
            [
                'name' => 'no_telp',
                'title' => 'No. Telepon',
                'type' => 'number',
                'required' => true,
                'placeholder' => 'Masukkan No. Telepon',
            ],
            [
                'name' => 'email',
                'title' => 'Email',
                'type' => 'email',
                'required' => true,
                'placeholder' => 'Masukkan Username',
            ],
            [
                'name' => 'password',
                'title' => 'Password',
                'type' => 'password',
                'required' => false,
                'placeholder' => 'Password',
            ],
            [
                'name' => 'can_login',
                'title' => 'Dapat Akses Sistem?',
                'type' => 'checkbox',
                'required' => false,
                'custom-class-wrapper' => 'col-md-3 col-3',
                'value' => 'Y',
                'placeholder' => '',
            ],

        ],
        "route" => [
            'index' => 'guest.index',
            'add' => 'guest.create',
            'show' => 'guest.show',
            'update' => 'guest.update',
            'edit' => 'guest.edit',
            'store' => 'guest.store',
            'detail' => 'guest.detail',
            'delete' => 'guest.destroy',
        ],
        "tableHead" => ["No", "Nama", "Kontak", "Identitas","Alamat","Aksi"],
        "tableColumns" =>["DT_RowIndex", "nama_lengkap", "kontak", "identity", "address", "action"],
    ];
    public function index()
    {
        $dataPage = $this->dataPage;
            $list = Guest::all();
            
            $akses = request()->attributes->get('hakAkses');
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][5]);
                unset($dataPage['tableColumns'][5]);
            }
            $data = (object) [
                'title' => 'Tamu',
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
            return view('pages.guest.index', compact('data'));
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
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        //
    }

    function ajax($list)
    {

        return DataTables::of($list)
        ->addIndexColumn()
        ->smart(false)
        ->addColumn("action", function ($row) {
                $akses = request()->attributes->get("hakAkses");
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus karyawan ' . $row->nama_lengkap . ' ?';

                $actionBtn = $akses["access_edit"] != 'Y' ? '': '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size: 24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size: 24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->addColumn("identity", function($row){
                return $row->identity_number . "<br>" . strtoupper($row->identity_type);
            })
            ->addColumn("kontak", function($row){
                return strtoupper($row->no_telp) . "<br>" . $row->email;
            })
            ->rawColumns(["action", "identity", "kontak"])
            ->make(true);
    }
}
