<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\RoomTypes;
use App\Models\Room;
use App\Models\Additional;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Attachment;
use Illuminate\Support\Facades\Validator;

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
        $roomType = RoomTypes::get();
        $additional = Additional::get();
        $roomAdd = [];
        $generalAdd = [];
        foreach ($additional as $key => $add) {
            if ($add->type == 'room') {
                $roomAdd[] = $add;
            }else {
                $generalAdd[] = $add;
            }
        }
        $bookingCode = $this->generateBookingCode();
        $data=(object)[
            'title' => 'Booking',
            'subtitle' => 'Reservasi',
            'active' => 'booking',
            'formAction' => route('booking.store'),
            'bookcode' => $bookingCode,
            'roomType' => $roomType,
            'additionalRoom' => $roomAdd,
            'generalAdd' => $generalAdd,
        ];
        
        return view('pages.booking.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $inputAttachment= [];
            $rules = [
                'nama_lengkap' => 'required',
                'email' => 'required',
                'no_telp' => 'required',
                'identity_type' => 'required',
                'identity_number' => 'required',
                'address' => 'required', // guest
                'book_reff' => 'required', // booking source
                'payment_method' => 'required',
                'room_type_id' => 'required|array', // rooms
                'room_type_id.*' => 'required|exists:room_types,id',
                'room_id' => 'required|array',
                'room_id.*' => 'required|exists:rooms,id',
                'total_guest' => 'required|array',
                'total_guest.*' => 'required|numeric',
                'check_in' => 'required|array',
                'check_in.*' => 'required|date',
                'check_out' => 'required|array',
                'check_out.*' => 'required|date',
                'payment_type' => 'required', // payment
                'payment_amount' => 'required|numeric',
            ];
            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
                return redirect()->back()->with('error', 'Data gagal divalidasi : ' . $validate->errors()->first());
            }
            $inputData=[];
            $booking = Booking::create($inputData);
            if ($request->hasFile('ktp_kk')) {
                $file = $request->file('ktp_kk');
                $path = storage_path('app/public/booking');
                $photo = 'booking/' . $this->compress($file, $path, 50);
                $inputAttachment[] = [
                            'reff_feature' => 'booking',
                            'file_url' => $photo,
                            'file_name' => basename($photo),
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => Storage::disk('public')->mimeType($photo),
                            'reff_id' => $booking->id,
                            'file_size' => Storage::disk('public')->size($photo),
                        ];
            }
            DB::commit();
            return redirect(route('booking.index'))->with('success', 'Berhasil menambah data booking');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan : ' . $th->getMessage());
        }
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

    private function generateBookingCode()
    {
        
        $now = Carbon::now();
        $dateFormatted = $now->format('dmy'); 
        $prefix = 'EZ-RSV' . $dateFormatted;

        $lastBooking = Booking::whereDate('created_at', $now->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        if (!$lastBooking) {
            $sequence = 1;
        } else {
            $lastCode = $lastBooking->booking_code;
            $lastSequence = (int) substr($lastCode, -3);
            $sequence = $lastSequence + 1;
        }
        $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        return $prefix .$sequenceFormatted;
    }

    
}
