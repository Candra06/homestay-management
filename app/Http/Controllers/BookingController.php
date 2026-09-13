<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\RoomTypes;
use App\Models\Attachment;
use App\Models\Rooms;
use App\Models\Additional;
use App\Models\Guest;
use App\Models\Voucher;
use App\Models\BookingRoom;
use App\Models\BookingAdditional;
use App\Models\BookingRoomAdditional;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InvoiceItem;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use DateTime;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public $dataPage = [
       
        "route" => [
            'index' => 'booking.index',
            'add' => 'booking.create',
            'show' => 'booking.show',
            'update' => 'booking.update',
            'edit' => 'booking.edit',
            'store' => 'booking.store',
            'detail' => 'booking.detail',
            'delete' => 'booking.destroy',
        ],
        "tableHead" => ["No", "Booking", "Kamar","Tanggal Reservasi", "Source", "Rincian Finansial", "Bayar","aksi"],
        "tableColumns" => ["DT_RowIndex", "booking", "room", "date_info","book_reff","total_price", "payment_status","action"],
    ];
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
            $dataPage = $this->dataPage;
            $list = Booking::with('guest','bookingRooms')->orderBy('created_at', 'DESC')->get();
            $akses = request()->attributes->get("hakAkses");
             if ($akses['access_edit'] != 'Y' && $akses['access_delete'] != 'Y') {
                unset($dataPage['tableHead'][count($dataPage['tableHead']) - 1]);
                unset($dataPage['tableColumns'][count($dataPage['tableColumns']) - 1]);
            }
            $data=(object)[
                'title' => 'Booking',
                'subtitle' => 'Reservasi',
                'active' => 'booking',
                "createBtn" => $akses['access_create'] == 'Y',
                'tableHead' => $dataPage['tableHead'],
                'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
                 "routeAdd" => route($dataPage['route']['add']),
                "routeData" => route($dataPage['route']['index']),
                'availableRooms' => $availableRooms,
            ];
             if (request()->ajax()) {
                return $this->ajax($list);
            }
            // $bookings = Booking::all();
            return view('pages.booking.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
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
        
        // return $request;
        DB::beginTransaction();
        try {
            $inputAttachment= [];
            $rules = [
                'guest_name' => 'required',
                'guest_email' => 'required',
                'phone' => 'required',
                'identity_type' => 'required',
                'identity_number' => 'required',
                'address' => 'required', // guest
                'book_reff' => 'required', // booking source
                'payment_method' => 'required',
                'payment_amount' => 'required|numeric',
            ];
            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
                return redirect()->back()->withInput()->with('error', 'Data gagal divalidasi : ' . $validate->errors()->first());
            }
            $guestData = [
                'nama_lengkap' => $request->guest_name,
                'email' => $request->guest_email,
                'no_telp' => $request->phone,
                'identity_type' => $request->identity_type,
                'identity_number' => $request->identity_number,
                'address' => $request->address,
            ];
            $guest = Guest::updateOrCreate([
                'identity_number' => $request->identity_number
            ], $guestData);
            $voucher = Voucher::where('code', $request->voucher_code)->first();
            $bookingData=[
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'booking_code' => $this->generateBookingCode(),
                'guest_id' => $guest->id,
                'book_reff' => $request->book_reff,
                'external_booking_id' => $request->has('external_booking_id') ? $request->external_booking_id : null,
                'tax' => $request->tax_value,
                'payment_method' => $request->payment_method,
                'subtotal' => $request->subtotal_value,
                'down_payment' => $request->dp_amount??0,
                'note' => $request->additional_notes,
                'total_payment' => $request->payment_amount,
                'grand_total' => $request->grand_total_value,
                'booking_status' => ($request->payment_amount == $request->grand_total_value) ? 'Approved' : 'Pending',
                'payment_status' => ($request->payment_amount == $request->grand_total_value) ? 'Paid' : (($request->dp_amount > 0) ? 'Part Paid' : 'Unpaid'),
                'paid_at' => ($request->payment_amount == $request->grand_total_value) ? $request->payment_date : null,
                'down_payment_paid_at' => ($request->dp_amount > 0) ? $request->payment_date : null,
                'amount_paid' => ($request->dp_amount > 0) ? $request->dp_amount : $request->payment_amount,
                'promo_id' => $request->has('promo_id') ? $request->promo_id : null,
                'voucher_id' => $request->has('voucher_id') ? $request->voucher_id : null,
                'discount_amount' => $request->has('discount_amount_value') ? ($request->discount_amount_value != null ? $request->discount_amount_value : 0) : 0,
            ];
            $booking = Booking::create($bookingData);
            $bookingRoomData = [];
            $bookingAddOnData = [];
            for ($i=0; $i < count($request->room_type); $i++) {
                $price = RoomTypes::where('id', $request->room_type[$i])->value('base_price');
                $checkIn = new DateTime($request->check_in[$i]);
                $checkOut = new DateTime($request->check_out[$i]);
                $nights = $checkIn->diff($checkOut)->days;
                $totalPrice = $price * $nights;
                $additionalAddOn = $request->input("additional_room-{$i}", []);
                $roomIns = BookingRoom::create([
                    'booking_id' => $booking->id,
                    'room_id' => $request->room_number[$i],
                    'price_per_night' => $price,
                    'total_price' => $totalPrice,
                    'checkin_date' => $request->check_in[$i],
                    'checkout_date' => $request->check_out[$i],
                ]);
                if ($roomIns) {
                        
                    foreach ($additionalAddOn as $key => $add) {
                        $addInfo = Additional::where('id', $add)->first();
                        $bookingAddOnData[] = [
                            'booking_room_id' => $roomIns->id,
                            'additional_id' => $add,
                            'price_per_additional' => $addInfo->price,
                            'total_price' => $addInfo->price * $nights,
                        ];
                    }
                }
            }
            
            BookingRoomAdditional::insert($bookingAddOnData);

            $invoiceData = [
                'booking_id' => $booking->id,
                'invoice_number' => $this->generateInvoiceCode(),
                'issue_date' => $request->payment_date,
                'due_date' => $request->payment_date,
                'subtotal' => $request->subtotal_value,
                'service_charge' => $request->service_charge_value??0,
                'tax_amount' => $request->tax_value??0,
                'discount' => $request->discount??0,
                'grand_total' => $request->grand_total_value,
                'amount_paid' => ($request->dp_amount > 0) ? $request->dp_amount : $request->payment_amount,
                'status' => $request->payment_amount == $request->grand_total_value ? 'Paid' : ($request->dp_amount > 0 ? 'Partial' : 'Unpaid'),
                'note' => $request->additional_notes,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            $invoice = Invoice::create($invoiceData);
            $invoiceItemData = [];
            for ($i=0; $i < count($request->room_type); $i++) {
                $price = RoomTypes::where('id', $request->room_type[$i])->first();
                $checkIn = new DateTime($request->check_in[$i]);
                $checkOut = new DateTime($request->check_out[$i]);
                $nights = $checkIn->diff($checkOut)->days;
                $totalPrice = $price->base_price * $nights;
                $invoiceItemData[] = [
                    'invoice_id' => $invoice->id,
                    'item_name' => 'Room ' . $price->type_name,
                    'quantity' => $nights,
                    'unit_price' => $price->base_price,
                    'total_price' => $totalPrice,
                ];
            }
            InvoiceItem::insert($invoiceItemData);
            // $bookingAdditionalData = [];
            // for ($i=0; $i < count($request->additional_id); $i++) {
            //     $bookingAdditionalData[] = [
            //         'booking_id' => $booking->id,
            //         'additional_id' => $request->additional_id[$i],
            //         'qty' => $request->qty[$i],s
            //     ];
            // }
            // BookingAdditional::insert($bookingAdditionalData);
             if ($request->hasFile('identity_image')) {
                $file = $request->file('identity_image');
                $path = storage_path('app/public/guest');
                $photo = 'guest/' . $this->compress($file, $path, 50);
                $imagePaths = [
                            'file_path'=>$photo,
                            'file_name'=> basename($photo),
                            'original_name'=>$file->getClientOriginalName(),
                            'file_size'=>Storage::disk('public')->size($photo),
                            'mime_type'=>Storage::disk('public')->mimeType($photo)
                        ];
                $inputAttachment = [
                    'reff_feature' => 'guest',
                    'file_url' => $imagePaths['file_path'],
                    'file_name' => $imagePaths['file_name'],
                    'original_name' => $imagePaths['original_name'],
                    'mime_type' => $imagePaths['mime_type'],
                    'reff_id' => $booking->id,
                    'file_size' => $imagePaths['file_size'],
                ];
                Attachment::create($inputAttachment);
            }

            DB::commit();
            return redirect(route('booking.index'))->with('success', 'Berhasil menambah data booking');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal disimpan : ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $bookingData = Booking::with(
                'guest', 
                'invoices', 
                'bookingRooms',
                'bookingRooms.room', 
                'bookingRooms.additionals', 
                'bookingRooms.additionals.additional', 
                'bookingRooms.room.roomType',
                'userCreate',
                'userUpdate'
            )->where('id', $booking->id)->first();
        
        $data=(object)[
            'title' => 'Detail Reservasi',
            'subtitle' => 'Reservasi',
            'active' => 'booking',
            'bookingData' => $bookingData,
        ];
        return view('pages.booking.detail', compact('data'));
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
        DB::beginTransaction();
        try {
             $oldAttachments = Attachment::where('reff_feature', 'guest')
                ->where('reff_id', $booking->guest_id)
                ->get();

            foreach ($oldAttachments as $old) {
                if ($old->file_url && Storage::disk('public')->exists($old->file_url)) {
                    Storage::disk('public')->delete($old->file_url);
                }
            }

            Attachment::where('reff_feature', 'guest')
                ->where('reff_id', $booking->guest_id)
                ->delete();
            $bookingRoom = BookingRoom::where('booking_id', $booking->id)->get();
            if ($bookingRoom) {
                foreach ($bookingRoom as $room) {
                   BookingRoomAdditional::where('booking_room_id', $room->id)->delete();
                }
                BookingRoom::where('booking_id', $booking->id)->delete();
            }
            BookingRoom::where('booking_id', $booking->id)->delete();
            BookingAdditional::where('booking_id', $booking->id)->delete();
            $invoices = Invoice::where('booking_id', $booking->id)->get();
            if ($invoices) {
                foreach ($invoices as $invoice) {
                    InvoiceItem::where('invoice_id', $invoice->id)->delete();
                }
            }
            Invoice::where('booking_id', $booking->id)->delete();
            $booking->delete();

            DB::commit();
            return redirect(route('booking.index'))->with('success', 'Berhasil menghapus data booking');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data gagal dihapus : ' . $th->getMessage());
        }
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

    private function generateInvoiceCode()
    {
        
        $now = Carbon::now();
        $yearFormatted = $now->format('Y'); 
        $monthFormatted = $now->format('m'); 
        $dayFormatted = $now->format('d'); 
        $prefix = 'EZ-INV/' . $yearFormatted .'/'. $monthFormatted .'/'. $dayFormatted .'/';

        $lastInvoice = Invoice::whereDate('created_at', $now->toDateString())
            ->orderBy('id', 'desc')
            ->withTrashed()
            ->first();
        if (!$lastInvoice) {
            $sequence = 1;
        } else {
            $lastCode = $lastInvoice->invoice_number;
            $lastSequence = (int) substr($lastCode, -3);
            $sequence = $lastSequence + 1;
        }
        $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        return $prefix .$sequenceFormatted;
    }

    public function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('booking', function($row){
                $html = '<div class="fw-bold text-primary">'.$row->booking_code.'</div>
                            <div class="text-dark fw-medium">'.$row->guest->nama_lengkap.'</div>';
                $html .= Helpers::generateStatus($row->booking_status);
                return $html;
            })
            ->addColumn('room', function($row){
                return $row->bookingRooms->count().' Kamar';
            })
            ->addColumn('date_info', function($row){
                return Helpers::tanggalTime($row->created_at);
            })
            ->addColumn('book_reff', function($row){
                $text = '';
                if ($row->book_reff == 'direct_wa') {
                    $text = 'Direct WhatsApp';
                } elseif ($row->book_reff == 'direct_walkin') {
                    $text = 'Onsite';
                } elseif ($row->book_reff == 'ota') {
                    $text = 'OTA '.$row->ota_name;
                }
                
                return $text;
            })
            ->addColumn('total_price', function($row){
                $html = '
                 <div class="fw-bold text-dark">
                                Grand Total: '.Helpers::rupiah($row->grand_total).'
                            </div>
                <div class="fs-12 text-muted">
                                Subtotal: '.Helpers::rupiah($row->subtotal).' • PPN (11%): '.Helpers::rupiah($row->tax).'
                            </div>
                            <div class="fs-12 text-success">
                                DP: '.Helpers::rupiah($row->down_payment).'
                            </div>
                            
                           
                            <div class="fs-11 text-muted">
                                Terbayar: '.Helpers::rupiah($row->amount_paid).'
                            </div>';
                return $html;
            })
           
            ->addColumn('payment_status', function($row){
                return Helpers::generateStatusPayment($row->payment_status);
            })
            ->addColumn('action', function ($row) {
                $akses = request()->attributes->get('hakAkses');
                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus booking '.$row->booking_code.' ?';

                // $actionBtn = $akses['access_edit'] != 'Y' ? '' : '<a href="'.$editRoute.'"><button class="btn-sm me-2 btn btn-warning" style="font-size:12px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn = '<a href="'.$detailRoute.'"><button class="btn-sm me-2 btn btn-primary" style="font-size:12px;"><span class="fe fe-eye"></span></button></a>';
                $actionBtn .= $akses['access_delete'] != 'Y' ? '' : '<button class="btn-sm mr-2 modal-effect btn btn-danger" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:12px;" onclick="deleteData(\''.$deleteRoute.'\', \''.$message.'\')" href="#modal-delete"><span class="fe fe-trash"></span></button>';

                return $actionBtn;
            })
            ->rawColumns(['action', 'booking', 'room','total_price','book_date','payment_status'])
            ->make(true);
    }

    public function paymentBooking(Request $request, $id) {
        DB::beginTransaction();
        
       try {
            $booking = Booking::find($id);
            $invoice = Invoice::where('booking_id', $id)->first();
            $updateBooking=[
                'amount_paid' => $booking->amount_paid + $request->amount,
                'total_payment' => $booking->total_payment + $request->amount,
                'paid_at' => $request->payment_date.' '.date('H:i:s'),
                'payment_status' => 'Paid',
                'booking_status' => 'Approved',
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()
            ];
            Booking::where('id', $id)->update($updateBooking);
            $updateInvoice = [
                'amount_paid' => $invoice->amount_paid + $request->amount,
                'status' => 'Paid',
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()
            ];
            Invoice::where('id', $invoice->id)->update($updateInvoice);
            $addPayment = [
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $invoice->invoice_number,
                'paid_at' => $request->tgl_bayar.' '.date('H:i:s'),
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()
            ];
            Payment::create($addPayment);
            $akun = FinancialAccount::where('account_code', '1-100')->first();
            // return $akun;
            $addFinance = [
                'financial_account_id'=> $akun->id,
                'transaction_date' => $request->tgl_bayar,
                'transaction_type' => 'income',
                'amount' => $request->amount,
                'description' => 'Pembayaran untuk invoice '.$invoice->invoice_number.' atas nama '.$booking->guest->nama_lengkap,
                'reference_type' => 'booking',
                'reference_id' => $booking->id,
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()
            ];

            FinancialTransaction::create($addFinance);
            
            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
       } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return redirect()->back()->with('error', 'Pembayaran gagal ditambahkan : '.$th->getMessage());
       }
    }

    public function checkInProcess($id, $type){
        DB::beginTransaction();
        try {
            $booking = Booking::with('bookingRooms')->where('id',$id)->first();
            $bookingRoom = $booking->bookingRooms;
            
            $updateBooking=[
                'booking_status' => $type == 'checkin' ? 'Checked-In' : 'Completed',
                'updated_by' => Auth::user()->id,
                'checkedin_at' => $type == 'checkin' ? Carbon::now() : $booking->checkedin_at,
                'updated_at' => Carbon::now()
            ];
            Booking::where('id', $id)->update($updateBooking);
            foreach ($bookingRoom as $key => $value) {
                Rooms::where('id', $value->room_id)->update([
                    'status' => $type == 'checkin' ? 'Terisi' : 'Cleaning',
                    'remarks' => $type == 'checkin' ? 'Sedang digunakan(#'.$booking->booking_code.')' : 'Kamar dibersihkan',
                    'updated_at' => Carbon::now()
                ]);
            }
            DB::commit();
            return response()->json([
                'success' =>true,
                'message' => $type == 'checkin' ? 'Check-In berhasil ditambahkan' : 'Check-Out berhasil ditambahkan',
                'id' =>$id,
            ]);
            
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' =>false,
                'message' => $type == 'checkin' ? 'Check-In gagal ditambahkan' : 'Check-Out gagal ditambahkan',
                'error' => $th->getMessage(),
                'id' => $id
            ]);
        }
        
    }
}
