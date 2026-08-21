<!DOCTYPE html>
<html>

<head>
    <title>Resi Pengiriman</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .box-resi {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 24px;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body>

    <div class="container">
        @foreach ($data as $item)
            <div class="box-resi">
                <table>
                    <tr>
                        <td style="width: 100px">Nomor Resi</td>
                        <td>:</td>
                        <td style="align-content: top;">{{ $item->no_resi }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td style="width: 10px">:</td>
                        <td style="align-content: top;">{{ $item->customer_name }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td style="width: 10px">:</td>
                        <td style="align-content: top;">{{ $item->shipping_address }}</td>
                    </tr>
                    <tr>
                        <td>Nomor HP</td>
                        <td style="width: 10px">:</td>
                        <td style="align-content: top;">{{ $item->customer_phone }}</td>
                    </tr>
                </table>
            </div>
        @endforeach

    </div>

</body>

</html>
