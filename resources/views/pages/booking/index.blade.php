@extends('template.app')
@section('title')
    {{ $data->title }}
@endsection
@section('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <script src="{{ url('assets') }}/plugins/fullcalendar/fullcalendar.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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

        .fc-timegrid-event.booking-end .fc-event-main,
        .fc-timegrid-event.booking-middle .fc-event-main {
            opacity: 0 !important;
            /* Gunakan opacity 0 agar seluruh div 2 baris menghilang di hari berikutnya */
            pointer-events: none;
            /* Mencegah elemen yang tak terlihat ini diklik */
        }

        /* 7. Matikan border putih bawaan Fullcalendar */
        .fc-timegrid-event-harness>.fc-timegrid-event {
            box-shadow: none !important;
            border-color: transparent !important;
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
                    <a href="{{ $data->routeAdd }}" class="me-2"><button class="btn btn-sm btn-primary">
                            <i class="fa fa-plus me-1"></i> Tambah Reservasi</button></a>
                @endif
                <button class="btn btn-sm btn-success"><i class="fa fa-file-excel me-1"></i> Export Reservasi </button>
            </div>
        </div>
    </div>
    <x-alert />

    <div class="row">
        @php
            $bg = ['bg-primary-gradient', 'bg-success-gradient', 'bg-warning-gradient', 'bg-info-gradient'];
        @endphp
        @foreach ($data->availableRooms as $key => $item)
            @php
                $idx = $key % count($bg);
            @endphp
            <div class="col-lg-4 col-xl-4 col-md-4 col-12">
                <a href="{{ url('room/list/' . $item->id) }}">
                    <div class="card {{ $bg[$idx] }} text-white ">
                        <div class="card-body">
                            <div class="mt-0 text-center">
                                <span class="text-white">{{ $item->type_name }}</span>
                                <h2 class="text-white mb-0">{{ $item->rooms_count }}</h2>
                            </div>

                        </div>
                    </div>
                </a>
            </div>
        @endforeach

    </div>
    </div>


    <div class="card ms-4 me-4">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">Reservasi</h4>
                <div class="custom-toggle" id="view-mode-toggle">
                    <span class="toggle-text">List</span>
                    <span class="toggle-slider"></span>
                </div>
            </div>
        </div>

        <div class="card-body ">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text" style="padding: 4px 0px">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" id="daterange" placeholder="Pilih tanggal reservasi" name="daterange"
                                class="form-control" value="" />
                        </div>
                    </div>

                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="booking_status" id="booking_status" class="form-control" required>
                            <option value="">Pilih Status Reservasi</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Completed">Completed</option>
                            <option value="Checked-In">Checked In</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="payment_status" id="payment_status" class="form-control" required>
                            <option value="">Pilih Status Pembayaran</option>
                            <option value="Pending">Pending</option>
                            <option value="Part Paid">Part Paid</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="source" id="source" class="form-control" required>
                            <option value="">Pilih Sumber Reservasi</option>
                            <option value="direct_walkin">Onsite</option>
                            <option value="direct_wa">Direct WhatsApp</option>
                            <option value="ota">OTA</option>
                        </select>
                    </div>
                </div>
            </div>
            @include('pages.booking.calendar')
            @include('pages.booking.list')
        </div>
    </div>
@endsection
@section('script')
    <!-- FULL CALENDAR JS -->
    <!-- Moment.js (Wajib untuk daterangepicker) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Daterangepicker JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.calendar-view').hide();

        });
        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                autoUpdateInput: false, // Biarkan kosong dulu sampai user memilih
                alwaysShowCalendars: true, // <--- Kunci utama agar kalender selalu tampil di sisi kanan
                showCustomRangeLabel: true,
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Minggu Ini': [moment().startOf('week'), moment().endOf('week')],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                    '1 Tahun Terakhir': [moment().subtract(1, 'year'), moment()]
                },
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' s/d ',
                    applyLabel: 'Pilih',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Hingga',
                    customRangeLabel: 'Pilih Sendiri',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                }
            }, function(start, end, label) {
                // Callback saat tanggal dipilih
                $('#daterange').val(start.format('YYYY-MM-DD') + ' s/d ' + end.format('YYYY-MM-DD'));
                console.log($('#daterange').val());

            });

            // Handle tombol batal agar input kembali bersih
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#table').DataTable().ajax.reload()
            });
        });
        $('#view-mode-toggle').on('click', function() {
            $(this).toggleClass('on');

            const $text = $(this).find('.toggle-text');
            const isON = $(this).hasClass('on');

            if (isON) {
                $text.text('Kalender');
                $('.table-view').hide();
                $('.calendar-view').show();
                // Panggil fungsi atau render ulang tampilan tabel di sini
            } else {
                $text.text('List');
                $('.calendar-view').hide();
                $('.table-view').show();
                // Panggil fungsi atau render ulang tampilan kalender di sini
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar-booking');
            if (!calendarEl) return;
            let today = new Date().toISOString().split('T')[0];
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialDate: today,
                navLinks: true,
                selectable: true,
                selectMirror: true,
                droppable: false,

                select: function(arg) {
                    // Logika select tanggal
                },

                eventClick: function(arg) {
                    console.log('eventClick', arg.event);
                },
                editable: false,
                dayMaxEvents: false,
                fixedWeekCount: false,
                contentHeight: 650,
                events: function(fetchInfo, successCallback, failureCallback) {
                    let url = "{{ url('booking/booked-rooms') }}";
                    let startDate = fetchInfo.start;
                    let baseDate = new Date(startDate);
                    let tanggal = new Date(baseDate.setDate(baseDate.getDate() + 15)).toISOString()
                        .split('T')[0];

                    let tahun = tanggal.split('-')[0];
                    let bulan = tanggal.split('-')[1];
                    let firstDayOfMonth = `${tahun}-${bulan}-01`;

                    let data = {
                        date: firstDayOfMonth,
                    };

                    $.ajax({
                        url: url,
                        type: "GET",
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            // PENTING: Gunakan array lokal penampung data (jangan pakai variabel global tanpa di-reset)
                            let listRoom = [];

                            if (response && response.data && Array.isArray(response.data)) {
                                response.data.forEach(function(item) {
                                    listRoom.push({
                                        title: `${item.room.room_number}-${item.room.room_type.type_name}`,
                                        booking_code: `${item.booking.guest.nama_lengkap}(${item.booking.booking_code})`,
                                        start: item.checkin_date,
                                        end: item.checkout_date,
                                        color: item.room.room_type
                                            .type_name == 'Superior Twin' ?
                                            '#0161e9' : item.room.room_type
                                            .type_name == 'Deluxe King' ?
                                            '#059969' : '#f76d2f',
                                    });
                                });
                            }

                            // Berikan data yang sudah bersih ke FullCalendar
                            successCallback(listRoom);
                        },
                        error: function(xhr, status, error) {
                            console.error("Gagal memuat data booking:", error);
                            failureCallback(error);
                            successCallback([]);
                        }
                    });

                },
                eventContent: function(arg) {
                    // Ambil data yang kita parsing tadi
                    let guestName = arg.event.title;
                    let bookingCode = arg.event.extendedProps.booking_code;

                    // Buat struktur HTML 2 baris (Bisa dikasih class Tailwind/Bootstrap)
                    let htmlElement = `
            <div class="custom-event-content">
                <div class="guest-name" style="font-weight: bold; font-size: 0.9em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    ${guestName}
                </div>
                <div class="booking-code" style="font-size: 0.75em; opacity: 0.9;">
                    ${bookingCode}
                </div>
            </div>
        `;

                    return {
                        html: htmlElement
                    };
                }
            });
            calendar.render();

            if (window.ResizeObserver) {
                const resizeObserver = new ResizeObserver(function(entries) {
                    // Paksa kalender merapikan ulang bentuknya setiap kali ukuran kontainer berubah / muncul
                    calendar.updateSize();
                });

                // Mulai pantau elemen kalender
                resizeObserver.observe(calendarEl);
            }
        });
        $("#table").DataTable({
            ajax: '{{ $data->routeData }}',
            processing: true,
            serverSide: true,
            stateSave: true,
            columns: JSON.parse(`{!! json_encode($data->tableColumns) !!}`)
        });

        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }
    </script>
@endsection
