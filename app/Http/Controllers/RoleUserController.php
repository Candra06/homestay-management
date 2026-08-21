<?php

namespace App\Http\Controllers;

use App\Models\RoleUser;
use App\Models\Menu;
use App\Models\RoleUserHasMenu;
use Illuminate\Http\Request;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RoleUserController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'code',
                'title' => 'Kode Role',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Masukkan Kode Role',
            ],
            [
                'name' => 'name',
                'title' => 'Nama',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Nama Role',
            ],
        ],
        "route" => [
            'index' => 'role.index',
            'add' => 'role.create',
            'show' => 'role.show',
            'update' => 'role.update',
            'edit' => 'role.edit',
            'store' => 'role.store',
            'detail' => 'role.detail',
            'delete' => 'role.destroy',
        ],
        "tableHead" => ["No", "Kode Role", "Role",  "Aksi"],
        "tableColumns" => ["DT_RowIndex", "code", "name", "action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = RoleUser::get();
            $akses = request()->attributes->get("hakAkses");
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][3]);
                unset($dataPage['tableColumns'][3]);
            }
            $data = (object)[
                'title' => 'Data Role',
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
            return view('pages.role-user.index', compact('data'));
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
            'title' => 'Tambah Data Role',
            'subtitle' => 'Data Role',
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
                'code' =>$request->code,
                'name' =>$request->name,
            ];

            $role = RoleUser::create($input);
            $menu = Menu::get();
            foreach ($menu as $key => $mn) {
                $ins = [
                    'id_role' => $role->id,
                    'id_menu' => $mn->id,
                    'access_list' => $mn->have_list,
                    'access_create' => $mn->have_create,
                    'access_edit' => $mn->have_edit,
                    'access_delete' => $mn->have_delete,
                ];
                RoleUserHasMenu::create($ins);
            }
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data role');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah data role'.$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoleUser $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoleUser $role)
    {
        $page = $this->dataPage;
        $data = (object) [
            'title' => 'Data Role User',
            'subtitle' => 'Edit Data',
            'type' => 'edit',
            'action' => route($page['route']['update'], ['role' => $role]),
            'data' => $role->toArray(),
            'forms' => $page['forms'],
        ];
        // return $data;
        return view('template.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoleUser $role)
    {
        try {
            DB::beginTransaction();
            $input = [
                'code' =>$request->code,
                'name' =>$request->name,
            ];

            RoleUser::where('id', $role->id)->update($input);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil mengubah data role');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal mengubah data role'.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoleUser $role)
    {
        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data role');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data role'.$th->getMessage());
        }
    }

    function ajax($list)
    {

        return DataTables::of($list)
        ->addIndexColumn()
        ->smart(false)
        ->addColumn("action", function ($row) {
                $akses = request()->attributes->get("hakAkses");
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $edtAkses = route('role-akses.edit', $row->id);
                $message = 'Apakah Anda yakin untuk menghapus role ' . $row->name . ' ?';
                // $akses["edit"] = $akses["edit"];
                $actionBtn = $akses["access_edit"] != 'Y'? '': '<a href="'. $editRoute .'"><button class=" btn-sm me-2 btn" style="font-size: 24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn .= $akses["access_edit"] != 'Y'? '': '<a href="'. $edtAkses .'"><button class=" btn-sm me-2 btn" style="font-size: 24px;"><span class="fe fe-lock"></span></button></a>';
                $actionBtn  .= $akses["access_delete"] != 'Y'? '':'<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" style="font-size: 24px;" data-bs-toggle="modal" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-edit"></span></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}
