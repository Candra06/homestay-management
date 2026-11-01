<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\PaymentConfirmation;
use App\Models\User;
use App\Models\RoomTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Services\DashboardService;

class AuthController extends Controller
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
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
            $date = Carbon::now()->format('Y-m-d');
            $category = FinancialAccount::whereIn('id', [17, 18, 19, 20, 29, 21, 22, 25, 26, 3, 8, 11, 12, 13, 14])->get();
            $data = (object) [
                'occupancyWeekly' => $this->dashboardService->getCountReservation(),
                'checkInCheckOut' => $this->dashboardService->getCountCheckInCheckOut(),
                'roomAvailable' => $this->dashboardService->getCountRoomAvailable($date),
                'income' => $this->dashboardService->getCountIncome($date),
                'finance' => $this->dashboardService->getNormalizedChartData(),
                'occupancy' => $this->dashboardService->getNormalizedOccupancyChartData(),
                'category' => $category,
            ];
            
            return view('dashboard', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getFinanceChart() {
        try {
            $data = $this->dashboardService->getNormalizedChartData();
            return response()->json([
                'success' =>true,
                'message' => 'Berhasil memuat data',
                'data'=>$data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' =>false,
                'message' => 'Gagal memuat data, err: '.$th->getMessage() ,
                'data'=>[],
            ], 500);
        }
    }
    public function getOccupancyChart() {
        try {
            $data = $this->dashboardService->getNormalizedOccupancyChartData();
            return response()->json([
                'success' =>true,
                'message' => 'Berhasil memuat data',
                'data'=>$data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' =>false,
                'message' => 'Gagal memuat data, err: '.$th->getMessage() ,
                'data'=>[],
            ], 500);
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
        // return $roomData;
        foreach ($roomData as $key => $room) {
            $fclt = [];
            $img = '';
            if (count($room->attachments) > 0) {
                $img = str_contains($room->attachments[0]->file_url, 'http') ?  $room->attachments[0]->file_url : url('/').'/storage/'.$room->attachments[0]->file_url;
            } else {
                $img = 'assets/img/photos/default-image.png';
            }
            foreach ($room->facilities as $key => $f) {
                $fclt[] = $f->facility->nama_fasilitas;
            }
            $rooms[] = [
                'slug' => $room->slug,
                'name' => $room->type_name,
                'price' => $room->base_price,
                'photo' => $img,
                'facility' => $fclt,

            ];
        }
        
        $data = (object) [
            'facility' => $facility,
            'rooms' => $rooms,
        ];
        

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
