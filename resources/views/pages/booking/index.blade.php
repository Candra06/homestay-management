@extends('template.app')
@section('title')
    {{ $data->title }}
@endsection
@section('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
    <!-- FULL CALENDAR CSS -->
    <link href='{{ url('assets') }}/plugins/fullcalendar/fullcalendar.css' rel='stylesheet' />
    <link href='{{ url('assets') }}/plugins/fullcalendar/fullcalendar.min.css' rel='stylesheet' media='print' />
    <style>
        /* CSS Styling */
        .custom-toggle {
            width: 100px;
            height: 28px;
            background-color: #00b9ff;
            /* Warna Merah saat OFF (Kalender) */
            border-radius: 4px;
            position: relative;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            box-sizing: border-box;
        }

        /* Teks di tengah */
        .custom-toggle .toggle-text {
            color: #ffffff;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 800;
            font-family: sans-serif;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            z-index: 1;
            transition: transform 0.25s ease;
        }

        /* Slider / Knob Putih */
        .custom-toggle .toggle-slider {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 12px;
            height: 24px;
            background-color: #f4f5f7;
            border-radius: 3px;
            transition: left 0.25s ease;
            z-index: 2;
        }

        /* --- STATE ON (Tabel) --- */
        .custom-toggle.on {
            background-color: #22c03b;
            /* Warna Hijau saat ON (Tabel) */
        }

        /* Pindahkan slider ke kanan saat ON */
        .custom-toggle.on .toggle-slider {
            left: 86px;
            /* 100px (width) - 12px (slider) - 2px (padding) */
        }

        /* Geser sedikit teks ke kiri saat slider ada di kanan agar tetap seimbang */
        .custom-toggle.on .toggle-text {
            transform: translateX(-4px);
        }

        .custom-toggle:not(.on) .toggle-text {
            transform: translateX(4px);
        }
    </style>
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
        <div class="d-flex my-xl-auto right-content">
            <div class="pe-1 mb-xl-0">
                @if ($data->createBtn)
                    <a href="{{ $data->routeAdd }}"><button class="btn btn-primary">
                            Tambah Pemesanan</button></a>
                @endif
            </div>
        </div>
    </div>
    <x-alert />

    <div class="row">
        <div class="col-lg-4 col-xl-4 col-md-4 col-12">
            <div class="card bg-primary-gradient text-white ">
                <div class="card-body">
                    <div class="mt-0 text-center">
                        <span class="text-white">Superior Twin Bed</span>
                        <h2 class="text-white mb-0">10</h2>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-4 col-md-4 col-12">
            <div class="card bg-danger-gradient text-white">
                <div class="card-body">
                    <div class="mt-0 text-center">
                        <span class="text-white">Superior King Bed</span>
                        <h2 class="text-white mb-0">10</h2>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-4 col-md-4 col-12">
            <div class="card bg-success-gradient text-white">
                <div class="card-body">
                    <div class="mt-0 text-center">
                        <span class="text-white">Deluxe</span>
                        <h2 class="text-white mb-0">15</h2>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">Reservasi</h4>
                <div class="custom-toggle" id="view-mode-toggle">
                    <span class="toggle-text">Kalender</span>
                    <span class="toggle-slider"></span>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('pages.booking.calendar')
            @include('pages.booking.list')
        </div>
    </div>
@endsection
@section('script')
    <!-- FULL CALENDAR JS -->
    <script src="{{ url('assets') }}/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="{{ url('assets') }}/js/fullcalendar.js"></script>

    <script type="text/javascript">
        $('#view-mode-toggle').on('click', function() {
            $(this).toggleClass('on');

            const $text = $(this).find('.toggle-text');
            const isON = $(this).hasClass('on');

            if (isON) {
                $text.text('Tabel');
                $('.calendar-view').hide();
                $('.table-view').show();
                // Panggil fungsi atau render ulang tampilan tabel di sini
            } else {
                $text.text('Kalender');
                $('.table-view').hide();
                $('.calendar-view').show();
                console.log('Mode saat ini: KALENDER (OFF)');
                // Panggil fungsi atau render ulang tampilan kalender di sini
            }
        });
    </script>
@endsection
