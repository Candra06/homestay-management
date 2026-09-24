<?php

namespace App\Http\Controllers;

use App\Models\Housekeeping;
use App\Models\RoomTypes;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Rooms;
use Carbon\Carbon;

class HousekeepingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
            $today = Carbon::today();

            $availableRooms = RoomTypes::withCount(['rooms' => function ($query) use ($today) {
                $query->where('status', 'Tersedia') // Kamar tidak rusak
                    ->whereDoesntHave('bookings', function ($q) use ($today) {
                        $q->where('checkin_date', '<=', $today)
                            ->where('checkout_date', '>', $today);
                    });
            }])->get();
           
            $akses = request()->attributes->get("hakAkses");
            
            $data=(object)[
                'title' => 'Housekeeping',
                'subtitle' => 'Reservasi',
                'active' => 'housekeeping',
                "createBtn" => $akses['access_create'] == 'Y',
                'availableRooms' => $availableRooms,
            ];
            
            // $bookings = Booking::all();
            return view('pages.housekeeping.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
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
    public function show(Housekeeping $housekeeping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Housekeeping $housekeeping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Housekeeping $housekeeping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Housekeeping $housekeeping)
    {
        //
    }

    public function updateStatus($id, Request $request) {
        try {
            Rooms::where('id', $id)->update([
                'status' => $request->status,
            ]);
            return redirect()->back()->with('success', 'Status berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Status gagal diupdate : '.$th->getMessage());
        }
    }
}
