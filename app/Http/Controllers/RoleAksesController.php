<?php

namespace App\Http\Controllers;
use App\Models\RoleUserHasMenu;
use Illuminate\Http\Request;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RoleAksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($role_akse)
    {

        $listMenu = RoleUserHasMenu::with('menu')->where('id_role', $role_akse)->get();
        // return $listMenu;
        $data = (object) [
            'title' => 'Role Akses Menu',
            'subtitle' => 'Edit Data',
            'type' => 'edit',
            'action' => route('role-akses.update', ['role_akse' => $role_akse]),
            'data' => $listMenu,
        ];
        return view('pages.role-akses.index', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $role_akse)
    {
        // return $request;
        try {
            $totalMenu = count($request->id_menu);
            $menu = $request->id_menu;
            for ($i=0; $i < $totalMenu; $i++) {
                $menuId = $menu[$i];
                $read = Request()->input("access_list-" . $menuId);
                $create = Request()->input("access_create-" . $menuId);
                $update = Request()->input("access_edit-" . $menuId);
                $delete = Request()->input("access_delete-" . $menuId);
                $access = [];
                if (isset($read)) {
                    $access["access_list"] = $read;
                } else {
                    $access["access_list"] = "N";
                }

                if (isset($update)) {
                    $access["access_edit"] = $update;
                } else {
                    $access["access_edit"] = "N";
                }

                if (isset($create)) {
                    $access["access_create"] = $create;
                } else {
                    $access["access_create"] = "N";
                }

                if (isset($delete)) {
                    $access["access_delete"] = $delete;
                } else {
                    $access["access_delete"] = "N";
                }
                $aksesMenu = RoleUserHasMenu::where('id_menu', $menuId)->where('id_role', $role_akse)->first();

                if ($aksesMenu) {
                    $aksesMenu->update($access);
                }
            }
            return redirect('role')->with('success', 'Berhasil mengubah akses');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
