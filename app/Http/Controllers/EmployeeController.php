<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\Employee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\RoleUser;

class EmployeeController extends Controller
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
            'index' => 'employee.index',
            'add' => 'employee.create',
            'show' => 'employee.show',
            'update' => 'employee.update',
            'edit' => 'employee.edit',
            'store' => 'employee.store',
            'detail' => 'employee.detail',
            'delete' => 'employee.destroy',
        ],
        "tableHead" => ["No", "Nama", "No. Telepon", "Email","Jabatan", "Status","Aksi"],
        "tableColumns" =>["DT_RowIndex", "nama_lengkap", "no_telp", "email", "jabatan", "status", "action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $dataPage = $this->dataPage;
            $list = Employee::with('user')->get();
            
            $akses = request()->attributes->get('hakAkses');
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][5]);
                unset($dataPage['tableColumns'][5]);
            }
            $data = (object) [
                'title' => 'Karyawan',
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
            return view('pages.employee.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forms = $this->dataPage['forms'];
       
        $data = (object) [
            'title' => 'Data Karyawan',
            'subtitle' => 'Tambah Data',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'routeBack' => url('/employee'),
            'action' => route('employee.store'),
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
        
        $validate = $request->validate([
                'nama_lengkap' => 'required',
                'email' => 'required|email',
                'no_telp' => 'required',
                'jabatan' => 'required',
                'status' => 'required',
                'password' => 'required_with:can_login',
            ]);
        
        DB::beginTransaction();
        try {
            if (!$validate) {
                return redirect()->back()->with('error', 'Harap lengkapi form')->withInput(request()->all());
            }
            $user = [];
            $inputEmployee =[
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
            ];
            if ($request->has('can_login') && $request->can_login == 'Y') {
                $userInput = [
                    'name' => $request->nama_lengkap,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'role' => in_array($request->jabatan,['SPV', 'Manajer']) ? 'Admin' : 'Front Office',
                    'role_id' =>in_array($request->jabatan,['SPV', 'Manajer']) ? 'Admin' : 'Front Office',
                ];
                $user = User::create($userInput);
                $inputEmployee['user_id'] = $user->id;
            }
            $employee = Employee::create($inputEmployee);
            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', $th->getMessage())->withInput(request()->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        // return $employee;
         try {
            $page = $this->dataPage;
            // return  $page['forms'];
            // [
            //     'name' => 'password',
            //     'title' => 'Password',
            //     'type' => 'password',
            //     'required' => false,
            //     'placeholder' => 'Password',
            // ],
            // [
            //     'name' => 'can_login',
            //     'title' => 'Dapat Akses Sistem?',
            //     'type' => 'checkbox',
            //     'required' => false,
            //     'custom-class-wrapper' => 'col-md-3 col-3',
            //     'value' => 'Y',
            //     'placeholder' => '',
            // ],
            $dataForm = [];
            $dataForm = $employee->load('user')->toArray();
            if ($dataForm['user_id'] != null) {
                $page['forms'][6]['other-attr'] = 'checked';
            }
            $page['forms'][5]['placeholder'] = 'Kosongkan jika tidak ingin merubah password';
            // return $dataForm;
            $data = (object) [
                'title' => 'Data Karyawan',
                'subtitle' => 'Edit Data',
                'type' => 'edit',
                'action' => route($page['route']['update'], ['employee' => $employee]),
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
    public function update(Request $request, Employee $employee)
    {
        
        DB::beginTransaction();
        $validate = $request->validate([
                'nama_lengkap' => 'required',
                'email' => 'required|email',
                'no_telp' => 'required',
                'jabatan' => 'required',
                'status' => 'required',
            ]);
        try {
            $inputEmployee = [
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
            ];
            if ($request->has('can_login') && $request->can_login == 'Y' && $employee->user_id != null) {
                $userInput = [
                    'name' => $request->nama_lengkap,
                    'username' => $request->email,
                    'role' => in_array($request->jabatan,['SPV', 'Manajer']) ? 'Admin' : 'Front Office',
                    'role_id' =>in_array($request->jabatan,['SPV', 'Manajer']) ? 'Admin' : 'Front Office',
                ];
                if ($request->has('password') && $request->password != null) {
                    $userInput['password'] = bcrypt($request->password);
                }
                $user = User::where('id', $employee->user_id)->update($userInput);
            }
            $employee->update($inputEmployee);
            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', $th->getMessage())->withInput(request()->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Employee $employee)
    {
         try {
            DB::beginTransaction();
            $userId= $employee->user_id;
            $employee->delete();
            if ($employee->user_id != null) {
                User::where('id', $userId)->delete();
            }
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data karyawan');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data karyawan. Err : '.$th->getMessage());
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
                $message = 'Apakah Anda yakin untuk menghapus karyawan ' . $row->nama_lengkap . ' ?';

                $actionBtn = $akses["access_edit"] != 'Y' ? '': '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn" style="font-size: 24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size: 24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->addColumn("status", function($row){
                $html = '<span class="badge '.($row->status == 'aktif' ? 'bg-success' : 'bg-danger').'">'.($row->status == 'aktif' ? 'Aktif' : 'Tidak Aktif').'</span>';
                return $html;
            })
            ->rawColumns(["action", "status"])
            ->make(true);
    }
}
