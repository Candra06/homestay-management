@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('css')
    <style>
        #toast-container.toast-top-center {
            top: 15% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
        }

        /* Mengatur tingkat opacity (transparansi) kotak toast */
        #toast-container>.toast {
            opacity: 0.90 !important;
            /* Ubah angka sesuai keinginan (0.0 - 1.0) */
            filter: alpha(opacity=90) !important;
        }
    </style>
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">Dashboard
                    <span class="text-muted mt-1 tx-13 ms-2 mb-0">/ {{ $data->subtitle }} </span> /
                </span>
                <span class="content-title ms-2 tx-13 mb-0 mt-1"> {{ $data->title }}</span>

            </div>
        </div>

    </div>


    <x-alert />

    <div class="card box-shadow">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">Detail Reservasi</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
        </div>

        <div class="card-body pd-r-0">
            @php
                Carbon\Carbon::setLocale('id_ID');
                $data = $data->bookingData;
                $room = [];
                $additional = [];
                foreach ($data->bookingRooms as $value) {
                    $checkIn = new DateTime($value->checkin_date);
                    $checkOut = new DateTime($value->checkout_date);
                    $nights = $checkIn->diff($checkOut)->days;
                    $totalPrice = $value->room->roomType->base_price * $nights;
                    $room[] = (object)[
                        'jenis' => 'Kamar',
                        'room_type' => $value->room->roomType->type_name,
                        'room_number' => $value->room->room_number,
                        'base_price' => App\Helper\Helpers::rupiah($value->room->roomType->base_price),
                        'checkin_date' => App\Helper\Helpers::tanggal($value->checkin_date),
                        'checkout_date' => App\Helper\Helpers::tanggal($value->checkout_date),
                        'nights' => $nights.' Malam',
                        'total_price' => App\Helper\Helpers::rupiah($totalPrice),
                    ];
                    foreach ($value->additionals as $add) {
                        $additional[] = (object)[
                            'jenis' => 'Additional',
                            'name' => $add->additional->name.'('.$value->room->room_number.')',
                            'price' => App\Helper\Helpers::rupiah($add->additional->price),
                            'total_price' => App\Helper\Helpers::rupiah($add->total_price),
                            'checkin_date' => App\Helper\Helpers::tanggal($value->checkin_date),
                            'checkout_date' => App\Helper\Helpers::tanggal($value->checkout_date),
                            'nights' => $nights.' Malam',
                        ];
                    }
                }
            @endphp
            <div class="col-md-12 col-xl-12 row row-sm px-0" id="confirm-container">

                <div class=" main-content-body-invoice">
                    <div class="card card-invoice">
                        <div class="card-body p-0">
                            <div class="invoice-header">
                                <h1 class="invoice-title">{{ $data->invoices[0]->invoice_number }}</h1>
                                <div class="billed-from">
                                    <h6>Ezzy Homestay.</h6>
                                    <p>Jl. Teratai No.51, Kec. Kaliwates,<br>Kabupaten Jember, Jawa Timur 68133<br>
                                        Tel No: +62 823-7454-7179<br>
                                        Email: ezzyhomestay@gmail.com</p>
                                </div><!-- billed-from -->
                            </div><!-- invoice-header -->
                            <div class="row mg-t-20">
                                <div class="col-md">
                                    <label class="tx-gray-600">Tamu</label>
                                    <div class="billed-to">
                                        <h6 id="cfrm_guest_name">{{ $data->guest->nama_lengkap }}</h6>
                                        <p id="cfrm_guest_telp">{{ $data->guest->no_telp }}</p>
                                        <p id="cfrm_guest_email">{{ $data->guest->email }}</p>
                                        <p id="cfrm_guest_address">{{ $data->guest->address }}</p>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <label class="tx-gray-600">Informasi Pemesanan Kamar</label>
                                    <p class="invoice-info-row"><span>Nomor Pemesanan</span> <span class="tx-bold"
                                            id="cfrm_booking_code">{{ $data->booking_code }}</span></p>
                                    <p class="invoice-info-row"><span>Tanggal Pemesanan</span> <span
                                            id="cfrm_booking_date">{{ App\Helper\Helpers::tanggalTime($data->created_at) }}</span>
                                    </p>
                                    <p class="invoice-info-row"><span>Tanggal Pembayaran:</span> <span
                                            id="cfrm_payment_date">{{ App\Helper\Helpers::tanggalTime($data->paid_at) }}</span>
                                    </p>
                                    <p class="invoice-info-row"><span>Petugas </span>
                                        <span>{{ $data->userCreate->name }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="table-responsive mg-t-40">
                                <table class="table table-invoice border text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th class="wd-10p tx-11">Jenis Layanan</th>
                                            <th class="wd-20p tx-11">Item/Tipe Kamar</th>
                                            <th class="wd-10p tx-11">Harga</th>
                                            <th class="wd-11p tx-11">Check-in</th>
                                            <th class="wd-11p tx-11">Check-out</th>
                                            <th class="wd-10p tx-center tx-11">Jumlah Malam</th>
                                            <th class="tx-right tx-11">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dt-booking">

                                        @foreach ($room as $item)
                                            
                                            <tr>
                                                <td class="tx-12">{{ $item->jenis }}</td>
                                                <td class="tx-12">
                                                    {{ $item->room_type . '(' . $item->room_number . ')' }}
                                                </td>
                                                <td class="tx-12">
                                                    {{ $item->base_price }}
                                                </td>
                                                <td class="tx-12">{{ $item->checkin_date }}
                                                </td>
                                                <td class="tx-12">{{ $item->checkout_date }}
                                                </td>
                                                <td class="tx-12 tx-center">{{ $item->nights }}</td>
                                                <td class="tx-12 tx-right">{{ $item->total_price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($additional as $item)
                                            
                                            <tr>
                                                <td class="tx-12">{{ $item->jenis }}</td>
                                                <td class="tx-12">
                                                    {{ $item->name}}
                                                </td>
                                                <td class="tx-12">
                                                    {{ $item->price }}
                                                </td>
                                                <td class="tx-12">{{ $item->checkin_date }}
                                                </td>
                                                <td class="tx-12">{{ $item->checkout_date }}
                                                </td>
                                                <td class="tx-12 tx-center">{{ $item->nights }}</td>
                                                <td class="tx-12 tx-right">{{ $item->total_price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-invoice border text-md-nowrap mb-0">
                                    <tbody id="dt-price">

                                        <tr>
                                            <td class="valign-middle" colspan="5" rowspan="7">
                                                <div class="invoice-notes">
                                                    <label class="main-content-label tx-13">Notes</label>
                                                    <p id="additional_notes_cfrm" class="tx-12" style="color: #000;">
                                                        {{ $data->note }}</p>
                                                </div><!-- invoice-notes -->
                                            </td>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Subtotal</td>
                                            <td class="tx-right wd-10.5p" id="total_sub">
                                                {{ App\Helper\Helpers::rupiah($data->subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Pajak (11%)</td>
                                            <td class="tx-right" style="width: 20%!important;" id="total_tax">
                                                {{ App\Helper\Helpers::rupiah($data->tax) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Diskon</td>
                                            <td class="tx-right" style="width: 20%!important;" id="total_discount">
                                                {{ App\Helper\Helpers::rupiah($data->discount_amount) }}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Down Payment</td>
                                            <td class="tx-right" style="width: 20%!important;" id="total_dp">
                                                {{ App\Helper\Helpers::rupiah($data->down_payment) }}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">
                                                {{ $data->grand_total != $data->amount_paid ? 'Sisa Pembayaran' : 'Terbayar' }}
                                            </td>
                                            @php
                                                $sisa = 0;
                                                if ($data->grand_total != $data->amount_paid) {
                                                    $sisa = $data->grand_total - $data->amount_paid;
                                                } else {
                                                    $sisa = $data->amount_paid;
                                                }

                                            @endphp
                                            <td class="tx-right" style="width: 20%!important;" id="total_remaining">
                                                {{ App\Helper\Helpers::rupiah($sisa) }}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;" class="tx-right  tx-uppercase tx-bold tx-inverse"
                                                colspan="2">Total
                                            </td>
                                            <td class="tx-right" style="width: 20%!important;">
                                                <h4 class="tx-primary tx-bold" id="total_all">
                                                    {{ App\Helper\Helpers::rupiah($data->grand_total) }}</h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--  -->
                        </div>
                        <div class="card-footer d-flex justify-content-end p-0 mt-2" style="border-top: 0px !important;">

                            <a class="btn btn-outline-primary me-1" href="{{ url('/booking') }}"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                                    <a class="btn btn-primary me-1" ata-bs-effect="effect-scale" data-bs-toggle="modal"
                                    href="#modal-payment"><i class="fa fa-money-bill"></i> Buat
                                    Pelunasan</a>
                            @if ($data->grand_total != $data->amount_paid)
                                {{-- <a class="btn btn-primary me-1" ata-bs-effect="effect-scale" data-bs-toggle="modal"
                                    href="#modal-payment"><i class="fa fa-money-bill"></i> Buat
                                    Pelunasan</a> --}}
                            @elseif ($data->grand_total == $data->amount_paid && $data->booking_status == 'Approved')
                                <button type="button" id="btn-checkin-process" class="btn btn-info me-1"><i
                                        class="fa fa-check"></i> Check In</button>
                            @elseif ($data->booking_status == 'Checked-In')
                                <button type="button" id="btn-checkout-process" class="btn btn-warning me-1"><i class="fe fe-log-out"></i> Check Out</button>
                            @endif


                            <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i>
                                Cetak</button>

                        </div>
                    </div>
                </div>
            </div><!-- COL-END -->

        </div>
    </div>
    <div class="modal fade" id="modal-payment">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Tambah Pelunasan</h6><button aria-label="Close" class="close"
                        data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/booking/payment/' . $data->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Sisa Pembayaran</label>
                            <input type="text" readonly
                                value="{{ App\Helper\Helpers::rupiah($data->grand_total - $data->amount_paid) }}"
                                class="form-control" placeholder="Masukkan Jumlah Pembayaran" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Jumlah Pembayaran<span class="tx-danger">*</span></label>
                            <input type="number" name="amount_display" id="amount_display" class="form-control input-display"
                                placeholder="Masukkan Jumlah Pembayaran" required>
                            <input type="hidden" name="amount" id="amount" class="form-control input-raw"
                                placeholder="Masukkan Jumlah Pembayaran" >
                        </div>
                        <div class="form-group">
                            <label for="tgl_bayar">Tanggal Pembayaran<span class="tx-danger">*</span></label>
                            <input type="date" name="tgl_bayar" id="tgl_bayar" class="form-control "
                                placeholder="Masukkan Tanggal Pembayaran" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran<span class="tx-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Bank Transfer">Transfer Bank</option>
                                <option value="Cash">Tunai</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Masukkan Catatan"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Tambah Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('input', '.input-display', function() {
            let $displayInput = $(this);
            let typedValue = $displayInput.val();

            let rawValue = typedValue.replace(/[^0-9]/g, '');

            let $rawInput = $displayInput.closest('.form-group').find('.input-raw');
            $rawInput.val(rawValue);

            if (rawValue) {
                $displayInput.val(formatRibuan(rawValue));
            } else {
                $displayInput.val('');
            }
        });
    });
    $('#btn-checkin-process').on('click', function(e){
        e.preventDefault();
        var statusBooking = "{{ $data->booking_status }}";
        var statusPayment = "{{ $data->payment_status }}";
        var checkInData = "{{ $data->bookingRooms->first()->checkin_date }}";
        const today = new Date().toISOString().split('T')[0];
        var id = "{{ $data->id }}";
        if(statusPayment != 'Paid'){
            toastr.warning('Pembayaran belum lunas, harap menyelesaikan pembayaran');
        }else if(checkInData > today){
            toastr.warning('Tanggal check in belum tiba, harap check in sesuai tanggal');
        }
        else{
            bookProcess('checkin');
        }
    });
    $('#btn-checkout-process').on('click', function(e){
        e.preventDefault();
        bookProcess('checkout');
    });
    function bookProcess(type) {
       $.ajax({
                url: "{{ url('booking/process/'.$data->id) }}"+`/${type}`,
                type: "GET",
                success: function(response) {
                    if(response.success){
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }else{
                        toastr.error(response.message);
                    }
                }
            });
    }
    function formatRibuan(angka) {
            if (!angka) return '';
            let numberString = angka.toString().replace(/[^,\d]/g, '');
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }
</script>
@endsection
