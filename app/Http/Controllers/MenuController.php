<?php

namespace App\Http\Controllers;


use App\Models\RoleUser;
use App\Models\Menu;
use App\Models\RoleUserHasMenu;
use Illuminate\Http\Request;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\GroupMenu;

class MenuController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'code',
                'title' => 'Kode Menu',
                'type' => 'text',
                'required' => true,
                'custom-class-wrapper' => 'col-4',
                'placeholder' => 'Masukkan Kode Menu',
            ],
            [
                'name' => 'group_menu_id',
                'title' => 'Grup Menu',
                'type' => 'select',
                'required' => true,
                'custom-class-wrapper' => 'col-4',
                'class' => 'select2-no-search',
                'placeholder' => 'Pilih grup menu',
                 'data' => [],
            ],
            [
                'name' => 'name',
                'title' => 'Nama',
                'type' => 'text',
                'required' => true,
                'custom-class-wrapper' => 'col-4',
                'placeholder' => 'Nama Menu',
            ],
            [
                'name' => 'icon',
                'title' => 'Icon Menu',
                'type' => 'text',
                'required' => true,
                'custom-class-wrapper' => 'col-6',
                'placeholder' => 'Masukkan class icon menu',
            ],
            [
                'name' => 'url',
                'title' => 'Url Menu',
                'type' => 'text',
                'required' => true,
                'custom-class-wrapper' => 'col-6',
                'placeholder' => 'Masukkan url menu',
            ],
            [
                'name' => 'have_list',
                'title' => 'Akses List',
                'type' => 'checkbox',
                'required' => false,
                'value' => 'Y',
                'placeholder' => '',
            ],
            [
                'name' => 'have_create',
                'title' => 'Akses Tambah',
                'type' => 'checkbox',
                'required' => false,
                'value' => 'Y',
                'placeholder' => '',
            ],
            [
                'name' => 'have_edit',
                'title' => 'Akses Ubah',
                'type' => 'checkbox',
                'required' => false,
                'value' => 'Y',
                'placeholder' => '',
            ],
            [
                'name' => 'have_delete',
                'title' => 'Akses Hapus',
                'type' => 'checkbox',
                'required' => false,
                'value' => 'Y',
                'placeholder' => '',
            ]
        ],
        "route" => [
            'index' => 'menu.index',
            'add' => 'menu.create',
            'show' => 'menu.show',
            'update' => 'menu.update',
            'edit' => 'menu.edit',
            'store' => 'menu.store',
            'detail' => 'menu.detail',
            'delete' => 'menu.destroy',
        ],
        "tableHead" => ["No", "Kode Menu", "Menu", "Icon", "URL Menu", "Aksi"],
        "tableColumns" => ["DT_RowIndex", "code", "name", "icon", "url", "action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataPage = $this->dataPage;
            $list = Menu::get();
            $akses = request()->attributes->get("hakAkses");
            if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][5]);
                unset($dataPage['tableColumns'][5]);
            }
            $data = (object)[
                'title' => 'Data Menu',
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
            return view('pages.menu.index', compact('data'));
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
        $parent = GroupMenu::get();
        $forms[1]['data'] = $parent->map(function($item) {
            return [
                'id' => $item->id,
                'val' => $item->name,
            ];
        });

        $data = (object) [
            'title' => 'Tambah Data Menu',
            'subtitle' => 'Data Menu',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            // 'routeBack' => url('/user'),
            'action' => route($this->dataPage['route']['store']),
            'data' => [],
            'forms' => $forms,
            // 'menus' => $this->getMenus(),
            // 'useEditor' => TRUE,
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
                'icon' =>$request->icon,
                'url' =>$request->url,
                'group_menu_id' =>$request->group_menu_id,
                'have_list' => $request->has("have_list") ? 'Y' : 'N',
                'have_create' => $request->has("have_create") ? 'Y' : 'N',
                'have_edit' => $request->has("have_edit") ? 'Y' : 'N',
                'have_delete' => $request->has("have_delete") ? 'Y' : 'N',
            ];

            $menu = Menu::create($input);
            $role = RoleUser::get();
            foreach ($role as $key => $rl) {
                $ins = [
                    'id_role' => $rl->id,
                    'id_menu' => $menu->id,
                    'group_menu_id' => $menu->group_menu_id,
                    'access_list' => $menu->have_list,
                    'access_create' => $menu->have_create,
                    'access_edit' => $menu->have_edit,
                    'access_delete' => $menu->have_delete,
                ];
                RoleUserHasMenu::create($ins);
            }
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data menu');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah data menu'.$th->getMessage());
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
    public function edit(Menu $menu)
    {
        $page = $this->dataPage;
        $forms = $page['forms'];
        $parent = GroupMenu::get();
        $forms[1]['data'] = $parent->map(function($item) {
            return [
                'id' => $item->id,
                'val' => $item->name,
            ];
        });
        $data = (object) [
            'title' => 'Data Menu',
            'subtitle' => 'Edit Data',
            'type' => 'edit',
            'action' => route($page['route']['update'], ['menu' => $menu]),
            'data' => $menu->toArray(),
            'forms' => $forms,
        ];
        // return $data;
        return view('template.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        try {
            DB::beginTransaction();
            $input = [
                'code' =>$request->code,
                'name' =>$request->name,
                'icon' =>$request->icon,
                'url' =>$request->url,
                'group_menu_id' =>$request->group_menu_id,
                'have_list' => $request->has("have_list") ? 'Y' : 'N',
                'have_create' => $request->has("have_create") ? 'Y' : 'N',
                'have_edit' => $request->has("have_edit") ? 'Y' : 'N',
                'have_delete' => $request->has("have_delete") ? 'Y' : 'N',
            ];
            Menu::where('id',$menu->id)->update($input);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil mengubah data menu');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal mengubah data menu'.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        try {
            DB::beginTransaction();
            $menu->delete();
            RoleUserHasMenu::where('id_menu', $menu->id)->delete();
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data menu');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data menu'.$th->getMessage());
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
                $message = 'Apakah Anda yakin untuk menghapus menu ' . $row->name . ' ?';
                // $akses["edit"] = $akses["edit"];
                $actionBtn = $akses["access_edit"] != 'Y' ?'':  '<a href="'. $editRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= $akses["access_delete"] != 'Y' ?'': '<button class="btn-sm mr-2 modal-effect btn" style="font-size:24px;" data-bs-effect="effect-scale" data-bs-toggle="modal" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><i class="fe fe-trash"></i></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}
