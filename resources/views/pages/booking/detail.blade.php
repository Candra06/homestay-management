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

                                        @foreach ($data->bookingRooms as $item)
                                            @php
                                                $checkIn = new DateTime($item->checkin_date);
                                                $checkOut = new DateTime($item->checkout_date);
                                                $nights = $checkIn->diff($checkOut)->days;
                                                $totalPrice = $item->room->roomType->base_price * $nights;
                                            @endphp
                                            <tr>
                                                <td class="tx-12">Kamar</td>
                                                <td class="tx-12">{{ $item->room->roomType->type_name . '(' . $item->room->room_number . ')' }}
                                                </td>
                                                <td class="tx-12">{{ App\Helper\Helpers::rupiah($item->room->roomType->base_price) }}
                                                </td>
                                                <td class="tx-12">{{ App\Helper\Helpers::tanggal($item->checkin_date) }}</td>
                                                <td class="tx-12">{{ App\Helper\Helpers::tanggal($item->checkout_date) }}</td>
                                                <td class="tx-center">{{ $nights }} Malam</td>
                                                <td class="tx-right">{{ App\Helper\Helpers::rupiah($totalPrice) }}</td>
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
                                                    <p id="additional_notes_cfrm" class="tx-12" style="color: #000;">{{$data->note}}</p>
                                                </div><!-- invoice-notes -->
                                            </td>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Subtotal</td>
                                            <td class="tx-right wd-10.5p" id="total_sub">{{ App\Helper\Helpers::rupiah($data->subtotal)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Pajak (11%)</td>
                                            <td class="tx-right" style="width: 20%!important;" id="total_tax">{{ App\Helper\Helpers::rupiah($data->tax)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Diskon</td>
                                            <td class="tx-right" style="width: 20%!important;" id="total_discount">{{ App\Helper\Helpers::rupiah($data->discount_amount)}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">Down Payment</td>
                                            <td class="tx-right" style="width: 20%!important;" id="total_dp">{{ App\Helper\Helpers::rupiah($data->down_payment)}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;" class="tx-right " colspan="2">{{ $data->grand_total != $data->amount_paid? 'Sisa Pembayaran' :'Terbayar' }}</td>
                                            @php
                                                $sisa = 0;
                                                if ($data->grand_total != $data->amount_paid) {
                                                    $sisa = $data->grand_total - $data->amount_paid;
                                                } else {
                                                    $sisa = $data->amount_paid;
                                                }
                                                
                                            @endphp
                                            <td class="tx-right" style="width: 20%!important;" id="total_remaining">{{ App\Helper\Helpers::rupiah($sisa)}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;" class="tx-right  tx-uppercase tx-bold tx-inverse" colspan="2">Total
                                            </td>
                                            <td class="tx-right" style="width: 20%!important;">
                                                <h4 class="tx-primary tx-bold" id="total_all">{{ App\Helper\Helpers::rupiah($data->grand_total)}}</h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--  -->
                        </div>
                        <div class="card-footer d-flex justify-content-end p-0 mt-2" style="border-top: 0px !important;">

                            <a class="btn btn-outline-primary me-1" href="{{ url('/booking') }}"><i class="fa fa-arrow-left"></i> Kembali</a>
                            @if ($data->grand_total != $data->amount_paid) 
                            <button class="btn btn-primary me-1" onclick=""><i class="fa fa-money-bill"></i> Buat Pelunasan</button>
                            @endif
                                
                            
                            <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
                            
                        </div>
                    </div>
                </div>
            </div><!-- COL-END -->

        </div>
    </div>
    
@endsection
@section('script')
@endsection
