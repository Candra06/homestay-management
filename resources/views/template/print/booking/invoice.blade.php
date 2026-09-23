<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h1 {
            color: #333;
        }

        h3 {
            margin-bottom: 4px;
            margin-top: 4px;
        }

        .main-logo {
            height: 40px;
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .align-center {
            align-items: center;
        }

        .gap-3 {
            gap: 3px;
        }

        .table-item {
            width: 100%;
            border-collapse: collapse;
        }

        .table-item tr th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table-item tr td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table-item tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }


        .table-item thead th {
            background-color: #d3d3d3;
            font-size: 13px !important;
            padding: 8px;
        }
    </style>
</head>
@php
    $data = (object) $dataInvoice['data'];
    $item = (object) $data->items;
    $booking = (object) $data->booking;
    $guest = (object) $booking->guest;
    $lastPayment = count($data->payments) > 0? $data->payments[count($data->payments) -1]:null;
@endphp

<body>
    <table style="width: 100%">
        <tr>
            <td style="text-align: left;width:70%; vertical-align:top;">
                <img src="{{ public_path('assets/img/brand/logo.png') }}"class="main-logo" alt="logo">
            </td>
            <td style="text-align: left;">
                <h3>Ezzy Homestay.</h3>
                <p style="margin-top:0px;">Jl. Teratai No.51, Kec. Kaliwates,<br>Kabupaten Jember, Jawa Timur 68133<br>
                    Tel No: +62 823-7454-7179<br>
                    Email: ezzyhomestay@gmail.com</p>
            </td>
        </tr>
    </table>
    {{-- <div class="flex justify-between">
        <img src="{{ public_path('assets/img/brand/logo.png') }}"class="main-logo" alt="logo">
        <div>
            <h3>Ezzy Homestay.</h3>
            <p style="margin-top:0px;">Jl. Teratai No.51, Kec. Kaliwates,<br>Kabupaten Jember, Jawa Timur 68133<br>
                Tel No: +62 823-7454-7179<br>
                Email: ezzyhomestay@gmail.com</p>
        </div>
    </div> --}}

    <h2>{{ $data->status == 'Paid' ? 'INVOICE' : 'BUKTI PEMBAYARAN' }}</h2>
    <div class="flex justify-between">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div>
                        <p><strong>Tagihan Kepada</strong></p>
                        <p style="line-height: 1.4"> {{ $guest->nama_lengkap }} <br>
                            {{ $guest->no_telp }} <br>
                            {{ $guest->email }} <br>
                            {{ $guest->address }} </p>
                    </div>

                </td>
                <td style="width: 40%;">
                    <div>
                        <table style="margin-top: 28px;">
                            <tr>
                                <td>Nomor {{ $data->status == 'Paid' ? 'Invoice' :'Pemabayaran' }}</td>
                                <td>:</td>
                                <td>{{ $data->status == 'Paid' ? $data->invoice_number : ($lastPayment->code??'-') }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td><strong>
                                    @if ($data->status == 'Paid')
                                        LUNAS
                                    @else
                                        DOWN PAYMENT
                                    @endif</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Terbit</td>
                                <td>:</td>
                                <td>{{ App\Helper\Helpers::tanggal($data->issue_date) }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>:</td>
                                <td>{{ App\Helper\Helpers::tanggal($data->due_date) }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pembayaran</td>
                                <td>:</td>
                                <td>
                                    {{ $lastPayment!=null ? App\Helper\Helpers::tanggal(explode(' ', $lastPayment->paid_at)[0]) :'-' }}
                                </td>
                            </tr>
                        </table>


                    </div>
                </td>
            </tr>
        </table>
    </div>



    <div>
        <table class="table-item" style="margin-top: 30px;">
            <thead>
                <th>Deskripsi</th>
                <th style="text-align: center;">Qty</th>
                <th style="">Harga</th>
                <th style="text-align: right;">Total</th>
            </thead>
            <tbody>
                @php
                    $grand_total = 0;
                    $service_charge = 0;
                    $tax_amount = 0;
                @endphp
                @foreach ($data->items as $row)
                    @php
                        $grand_total += $row->grand_total;
                        $service_charge += $row->service_charge;
                        $tax_amount += $row->tax_amount;
                    @endphp
                    <tr>
                        <td>{{ $row->item_name }}</td>
                        <td style="text-align: center;">{{ $row->quantity }}</td>
                        <td style="">{{ App\Helper\Helpers::rupiah($row->unit_price) }}</td>
                        <td style="text-align: right;">{{ App\Helper\Helpers::rupiah($row->total_price) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">Subtotal</td>
                    <td style="text-align: right;"><strong>{{ App\Helper\Helpers::rupiah($data->subtotal) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Service Charge</td>
                    <td style="text-align: right;">
                        <strong>{{ App\Helper\Helpers::rupiah($data->service_charge) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Diskon</td>
                    <td style="text-align: right;">
                        <strong>{{ App\Helper\Helpers::rupiah($booking->discount_amount * -1) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Pajak</td>
                    <td style="text-align: right;"><strong>{{ App\Helper\Helpers::rupiah($data->tax_amount) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Metode Pembayaran</td>
                    <td style="text-align: right;"><strong>{{ $booking->payment_method }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td style="text-align: right;">
                        <strong>{{ App\Helper\Helpers::rupiah($data->grand_total) }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
