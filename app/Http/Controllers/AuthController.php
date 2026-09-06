<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfirmation;
use App\Models\User;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\Facades\DataTables;

class AuthController extends Controller
{
    public function login()
    {
        if (! Auth::user()) {
            return view('auth.login');
        } else {

            return redirect('/dashboard');
        }
    }

    public function submitLogin(Request $request)
    {
        try {
            $credential = $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
            // return $credential;
            $user = null;
            // $credential["status"] = "Aktif";
            if (Auth::attempt($credential)) {
                // Helpers::saveLog(date("d-m-Y"), "akun", "login");

                return redirect('/dashboard');
            }
            $user = User::where('username', $request->email)->first();

            if (isset($user)) {
                return redirect()->back()->with('error', 'Maaf, akun Anda sedang tidak aktif')->withInput(request()->all());
            }

            return redirect()->back()->with('error', 'Email atau kata sandi salah')->withInput(request()->all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dashboard()
    {
        try {
            $rolesCode = Auth::user()->roles;
            $data = (object) [];
           
            return view('dashboard', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return redirect('/');
    }

    public function filterData($list, $count, $totalPaid, $totalKomisi)
    {

        $start = request()->start_date;
        $end = request()->end;
        if (request()->start_date != null && request()->end != null) {
            $list->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
            $count->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
            $totalPaid->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
            $totalKomisi->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
        }

        return ['list' => $list->get(), 'count' => $count->first(), 'totalPaid' => $totalPaid->sum('price'), 'totalKomisi' => $totalKomisi->sum('fee')];
    }

    public function ajax($list)
    {
        // $akses = request()->attributes->get("hakAkses");

        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->make(true);
    }

    public function convertImage()
    {
        try {
            $data = [];
            $query = PaymentConfirmation::with('product', 'product.report')->where('status', 'Done')->orderBy('id', 'DESC')->get();
            // return $query;
            foreach ($query as $q) {
                $product = [];
                foreach ($q->product as $p) {
                    $tmpProduct = [
                        'name' => $p->report[0]['unit_name'],
                        'qty' => 1,
                    ];
                    array_push($product, $tmpProduct);
                }
                $tmp = [
                    'resi' => $q->no_resi,
                    'receiver_address' => $q->shipping_address,
                    'reciever_name' => $q->customer_name,
                    'reciever_city' => $q->shipping_city,
                    'weight' => $q->total_weight,
                    'items' => $product,
                ];
                array_push($data, $tmp);
            }
            $data = $data[0];
            $html = view('template.resi-print', compact('data'))->render();
            $path = storage_path('app/public/resi/'.$data['resi'].'.png');
            Browsershot::html($html)
                ->setNodeBinary("C:\Program Files\nodejs\node.exe")                                                // or where node.exe is
                                                                                                               // adjust path if needed
                ->setIncludePath("C:\Program Files\nodejs;C:\Users\rezaa\AppData\Roaming\npm;C:\Windows\System32") // required on Windows
                ->noSandbox()
                ->windowSize(400, 600)
                ->save($path);

            // return $data;
            return view('template.resi-print', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function landingPage()
    {
        $facility = [
            [
                'name' => 'Kolam Renang',
                'image' => 'fac_swimming_pool.jpeg',
                'description' => '',
            ],
            [
                'name' => 'Teras',
                'image' => 'fac_teras.jpeg',
                'description' => '',
            ],
            [
                'name' => 'Dapur',
                'image' => 'fac_kitchen.jpeg',
                'description' => '',
            ],
        ];
        $roomData = RoomTypes::with('attachments', 'facilities.facility')->get();
        $rooms = [];
        foreach ($roomData as $key => $room) {
            $fclt = [];
            foreach ($room->facilities as $key => $f) {
                $fclt[] = $f->facility->nama_fasilitas;
            }
            $rooms[] = [
                'slug' => $room->slug,
                'name' => $room->type_name,
                'price' => $room->base_price,
                'photo' => url('/').'/storage/'.$room->attachments[0]->file_url,
                'facility' => $fclt,

            ];
        }
        
        $data = (object) [
            'facility' => $facility,
            'rooms' => $rooms,
        ];
        // return $data;

        return view('frontend.index', compact('data'));
    }

    public function roomDetail($slug) {
        
        try {
            $detail = RoomTypes::with('attachments', 'facilities.facility')->where('slug', $slug)->first();
            if ($detail) {
                $data = (object) [
                    'rooms' => $detail,
                ];
                // return $data;

                return view('frontend.rooms-detail', compact('data'));
            } else {
                # code...
            }
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
