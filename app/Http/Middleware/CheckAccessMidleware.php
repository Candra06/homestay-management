<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessMidleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $listAccess = Auth::user()->checkAccess;
        $currentUrl = $request->route()->uri();
        $explodeUrl = explode("/", $currentUrl);
        $firstUrl = $explodeUrl[0];
        $checkMenu = Menu::where("url", "LIKE", "%$firstUrl%")->first();
        $currentMethod = $request->method();

        if ($checkMenu) {
            $menuSelected = [];
            foreach ($listAccess as $vl) {
               if ($vl->id_menu == $checkMenu->id) {
                    $menuSelected = $vl;
               }
            }
            $menuSelected = $menuSelected->toArray();
            $hakAkses = ["access_list" => $menuSelected['access_list'], "access_create" => $menuSelected['access_create'], "access_edit" => $menuSelected['access_edit'], "access_delete" => $menuSelected['access_delete']];

            if ($menuSelected) {
                $keyAccess = "";
                if ($currentMethod == "GET" && count($explodeUrl) == 1) {
                    $keyAccess = "access_list";
                }else if ($currentMethod == "DELETE") {
                    $keyAccess = "access_delete";
                }
                if (count($explodeUrl) >= 2) {
                    if ($currentMethod == "GET" && $explodeUrl[1] == "add") {
                        $keyAccess = "access_create";
                    }
                }
                if (count($explodeUrl) >= 3) {
                    if ($currentMethod == "GET" && $explodeUrl[2] == "edit") {
                        $keyAccess = "access_edit";
                    }
                }

                if (isset($menuSelected[$keyAccess])) {
                    if ($menuSelected[$keyAccess] != "Y") {
                        return response()->view('template.403', [], 403);
                    }

                }

                $request->attributes->add(["hakAkses" => $hakAkses]);
            }
        } else if($currentUrl == 'logout'){
            return $next($request);
        } else {
            return response()->view('template.403', [], 403);
        }

        return $next($request);
    }
}
