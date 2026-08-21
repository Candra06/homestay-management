<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'name',
                'title' => 'Nama',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Masukkan Nama',
            ],
            [
                'name' => 'username',
                'title' => 'Username',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Masukkan Username',
            ],
            [
                'name' => 'password',
                'title' => 'Password',
                'type' => 'password',
                'required' => true,
                'placeholder' => 'Password',
            ],
            [
                'name' => 'role_id',
                'title' => 'Role',
                'type' => 'select',
                'data' => [],
                'class' => 'select2-no-search',
                'required' => true,
                'placeholder' => 'Pilih Role',
            ],

        ],
        "route" => [
            'index' => 'user.index',
            'add' => 'user.create',
            'show' => 'user.show',
            'update' => 'user.update',
            'edit' => 'user.edit',
            'store' => 'user.store',
            'detail' => 'user.detail',
            'delete' => 'user.destroy',
        ],
        "tableHead" => ["No", "Nama", "Username", "Role","Aksi"],
        "tableColumns" =>["DT_RowIndex", "name", "username", "role_name", "action"],
    ];

    //table property
    protected static $tableHead = ["No", "Nama", "Username", "Role","Aksi"];
    protected static $tableColumns = ["DT_RowIndex", "name", "username", "role_name", "action"];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = User::with('roles')->get();
            // return $list;
            $akses = request()->attributes->get("hakAkses");
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][4]);
                unset($dataPage['tableColumns'][4]);
            }
            $data = (object)[
                'title' => 'List Data User',
                "createBtn" => $akses['access_create']=='Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                "routeAdd" => route('user.create'),
                "routeData" => route('user.index'),
                'data' => $list,
            ];

            if (request()->ajax()) {
                return $this->ajax($list);
            }
            // return $data;
            return view('pages.user.index', compact('data'));
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
        // unset($forms[11]);
        // unset($forms[10]);
        $roles = RoleUser::get();
        $role = [];
        foreach ($roles as $key => $value) {
            $tmp = [
                'id' => $value->id,
                'val' => $value->name,
            ];
            array_push($role, $tmp);
        }
        $forms[3]['data'] = $role;
        $data = (object) [
            'title' => 'Data User',
            'subtitle' => 'Tambah Data',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'routeBack' => url('/user'),
            'action' => route('user.store'),
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
            User::create([
                'username' => $request->username,
                'name' => $request->name,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data user');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah data user'.$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $forms = $this->dataPage['forms'];
        $roles = RoleUser::get();
        $role = [];
        foreach ($roles as $key => $value) {
            $tmp = [
                'id' => $value->id,
                'val' => $value->name,
            ];
            array_push($role, $tmp);
        }
        $forms[3]['data'] = $role;
        $forms[2]['required'] = false;

        $data = (object) [
            'title' => 'Data User',
            'subtitle' => 'Tambah Data',
            'base_title' => 'Tambah Data',
            'type' => 'edit',
            'action' => route('user.update', ['user'=>$user->id]),
            'data' => $user->toArray(),
            'forms' => $forms,
        ];
        return view('template.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        DB::beginTransaction();
        try {
            $input = [
                'username' => $request->username,
                'name' => $request->name,
                'role_id' => $request->role_id,
            ];
            if ($request->password) {
                $input['password'] = bcrypt($request->password);
            }
            User::where('id', $user->id)->update($input);

            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil mengubah data user');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal mengubah data user'.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return redirect(route('user.index'))->with('success', 'Berhasil menghapus data user');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data user'.$th->getMessage());
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
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus user ' . $row->name . ' ?';

                $actionBtn = $akses["access_edit"] != 'Y' ? '': '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size: 24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size: 24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->addColumn("role_name", function ($row) {
                return $row->roles->name;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}


