<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
        }

        .receipt {
            width: 330px; /* suitable for 80mm paper */
            margin: auto;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .items td {
            padding: 4px 0;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }

        .address td{
            vertical-align: top;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center">
            <h2>My Store</h2>
            <p>Jl. Contoh No.123, Jakarta</p>
            <p>Telp: 0812-3456-7890</p>
        </div>

        <div class="divider"></div>

        <p>
            Date: {{ now()->format('Y-m-d H:i:s') }}<br>
        </p>
        <table  width="100%" class="address">
            <tr>
                <td>Pengirim :</td>
                <td>Penerima :</td>
            </tr>
            <tr>
                <td><strong>Sukalelang</strong></td>
                <td><strong>{{$data['reciever_name']}}</strong></td>
            </tr>
            <tr>
                <td>
                Perumahan alam hijau blok F1-34 \nBotosari,dukuh mencek\nKecamatan sukorambi \nKabupaten jember
                </td>
                <td>
                    {{$data['receiver_address']}}
                </td>
            </tr>
        </table>
        <table class="items" width="100%">
            @foreach($data['items'] as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="text-right">{{ $item['qty'] }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

       

        <div class="divider"></div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
        </div>
    </div>

    <script>
        // window.print();
    </script>
</body>
</html>
