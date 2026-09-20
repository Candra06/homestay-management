<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Pemesanan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #333;
            font-weight: bold;
        }

        .main-logo {
            height: 60px;
        }
    </style>
</head>
@php
    $data = (object) $invoice;
    $booking = (object) $data->booking;
    $guest = (object) $booking->guest;
@endphp

<body>
    <div class="invoice-box">
        <img src="https://ezzy-homestay.com/assets/img/brand/logo-landscape.png"class="main-logo" alt="logo">

        <h1>Hi, {{ $guest->nama_lengkap }}</h1>
        <p>Kami sudah menerima pembayaran untuk pesananmu dan melampirkan buktinya di bawah ini. Setelah pesanan
            dikonfirmasi, kami akan mengirimkan e-tiket di email yang berbeda.</p>

        <br>
        <p>Cheers,</p>
        <p><strong>Management Ezzy Homestay</strong>
        </p>


    </div>
</body>

</html>
