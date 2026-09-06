<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=(object)[
            'title' => 'Booking',
            'subtitle' => 'Reservasi',
            'active' => 'booking',
            'tableHead' => ['Kode Booking','Tamu', 'Jumlah Kamar','Tanggal Check-in', 'Tanggal Check-out', 'Source','Status', 'Aksi'],
            'createBtn' => true,
            'routeAdd' => route('booking.create'),
        ];
        // $bookings = Booking::all();
        return view('pages.booking.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data=(object)[
            'title' => 'Booking',
            'subtitle' => 'Reservasi',
            'active' => 'booking',
            'formAction' => route('booking.store'),
        ];
        return view('pages.booking.form', compact('data'));
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
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
