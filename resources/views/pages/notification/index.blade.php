@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->subtitle }} </span><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>
            </div>

        </div>

    </div>

    {{-- <div class="card box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">Scan Device</h4>
            @if (!$data->device_broadcast->connected)

            <span>Silakan scan QR untuk login</span>
            @endif
        </div>
        <div class="card-body p-3">
            @if (!$data->device_broadcast->connected)

                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ $data->device_broadcast->qr }}" alt="QR Code" style="width: 300px; height: 300px;">
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">
                            <h6>Petunjuk</h6>
                            <ol>
                                <li>Buka aplikasi WA di HP anda</li>
                                <li>Tap menu / Setting dan pilih Linked Device (Tautkan Perangkat)</li>
                                <li>Scan QR Code yang tampil</li>
                                <li class="text-danger">QR Code hanya berlaku 10 detik. Jika gagal atau melebihi 10 detik, refresh halaman dengan menekan tombol F5 atau CTRL+R</li>
                                <li>Jika pada aplikasi whatsapp berhasil terhubung, silahkan refresh halaman dengan menekan tombol F5 atau CTRL+R</li>
                            </ol>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-success-transparent p-3" role="alert">
                    Device sudah terhubung.
                </div>
            @endif



        </div>
    </div> --}}

    <div class="card box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $data->title }}</h4>
        </div>
        <div class="card-body p-0 mt-3">

            <div class="row p-3 text-center mb-3">
                <div class="col-md-6 col-sm-6 col-6">
                    <a href="{{ url('/notification/startBid') }}" class="btn btn-primary btn-block">Mulai Lelang</a>

                </div>
                <div class="col-md-6 col-sm-6 col-6">
                    <a href="{{ url('/notification/endBid') }}" class="btn btn-secondary btn-block">Selesai Lelang</a>

                </div>
                <div class="col-md-6 col-sm-6 col-6">

                </div>
            </div>

        </div>
    </div>
    @php
        $date1 = new DateTime($data->cron->updated_at);
        $date2 = new DateTime(Carbon\Carbon::now());
        $interval = $date1->diff($date2);
        $minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

    @endphp
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-header">
                    <h4 class="card-title mb-1">Broadcast Pemenang Lelang</h4>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="row p-3 mb-3">
                        <div class="col-md-4">
                            <label for="reminder">Tanggal Selesai Lelang</label>
                            <input type="date" class="form-control" name="date" id="date">
                        </div>

                        <div class="col-md-6 mt-1">
                            <label for="" class=""> </label>
                            <button type="button" id="send" class="btn btn-primary mt-4">Broadcast Pemenang</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">

            <div class="card box-shadow-0 {{ $minutes > 10 ? 'bg-danger-transparent' : 'bg-success-transparent' }}">
                <div class="card-header {{ $minutes > 10 ? 'bg-danger-transparent' : 'bg-success-transparent' }}">
                    <h4 class="card-title mb-0 {{ $minutes > 10 ? 'text-danger' : 'text-success' }}">Cron Job Monitoring
                    </h4>
                    {{-- <span>Last Running</span> --}}
                </div>
                <div class="card-body p-0{{ $minutes > 10 ? '' : ' mb-4 mt-4' }}">
                    <div class="row ms-2 mb-3">
                        <div class="col-md-12 col-sm-12">
                            <strong
                                style="font-size:32px;">{{ App\Helper\Helpers::tanggalFormat($data->cron->updated_at, 'H:i') }}</strong><span>&nbsp;&nbsp;{{ App\Helper\Helpers::tanggalFormat($data->cron->updated_at, 'd M Y') }}</span></br>
                            <span>Last Running</span>
                        </div>

                    </div>
                    @if ($minutes > 10)
                        <div class="m-3">
                            <a href="{{ route('run-cron') }}" class="btn btn-block btn-danger">Aktifkan Cron Job</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#send').click(function() {
                // Set the date parameter
                let dateParam = $('#date').val(); // Replace with dynamic value if needed

                if (dateParam == '') {
                    swal({
                        text: "Tanggal selesai lelang harus diisi",
                        type: "error"
                    });
                } else {
                    $.ajax({
                        url: 'https://sukalelang.id/notification/cron.php',
                        type: 'GET',
                        data: {
                            date: dateParam
                        }, // Send the date as a query parameter
                        timeout: 300000,
                        success: function(response) {

                            swal({
                                text: "Berhasil mengirim broadcast",
                                type: "success"
                            });
                        },
                        error: function(xhr, status, error) {
                            swal({
                                text: "Gagal mengirim broadcast",
                                type: "error"
                            });

                        }
                    });
                }

            });
        })
    </script>
@endsection
