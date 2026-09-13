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
@section('main') <div class="breadcrumb-header justify-content-between">
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
                <h4 class="card-title mg-b-0">Tambah Reservasi</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
        </div>

        <div class="card-body pd-r-0">
            <form action="{{ url('/booking') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div id="booking-container" class="d-block row">
                    {{-- Guest --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title mg-b-0">Data Tamu</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col form-group">
                                            <label class="tx-12" for="name">Nama Tamu<span
                                                    class="tx-danger">*</span></label>
                                            <input type="text" name="guest_name" id="guest_name"
                                                class="form-control form-control-sm" placeholder="Masukkan Nama Tamu"
                                                required>
                                        </div>
                                        <div class="col form-group">
                                            <label class="tx-12" for="email">Email<span
                                                    class="tx-danger">*</span></label>
                                            <input type="email" name="guest_email" id="guest_email"
                                                class="form-control form-control-sm" placeholder="Masukkan Email Tamu"
                                                required>
                                        </div>
                                        <div class="col form-group">
                                            <label class="tx-12" for="phone">Telepon/Whatsapp<span
                                                    class="tx-danger">*</span></label>
                                            <input type="tel" name="phone" id="phone"
                                                class="form-control form-control-sm input-number"
                                                placeholder="Masukkan Telepon/Whatsapp" required>
                                        </div>
                                        <div class="col form-group">
                                            <label class="tx-12" for="identity_type">Tipe Identitas<span
                                                    class="tx-danger">*</span></label>
                                            <select name="identity_type" id="identity_type"
                                                class="form-control form-control-sm" required>
                                                <option value="">Pilih Tipe Identitas</option>
                                                <option value="ktp">KTP</option>
                                                <option value="sim">SIM</option>
                                                <option value="passport">Passport</option>
                                                <option value="other">Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col form-group">
                                            <label class="tx-12" for="identity_number">Nomor Identitas<span
                                                    class="tx-danger">*</span></label>
                                            <input type="text" name="identity_number" id="identity_number"
                                                class="form-control form-control-sm input-number"
                                                placeholder="Masukkan Nomor Identitas" required>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-6 form-group">
                                            <label class="tx-12" for="identity_number">Alamat<span
                                                    class="tx-danger">*</span></label>
                                            <textarea class="form-control form-control-sm" name="address" id="address" placeholder="Masukkan Alamat"></textarea>
                                        </div>
                                        <div class="col form-group">
                                            <label class="tx-12" for="identity_number">Foto Identitas<span
                                                    class="tx-danger">*</span></label>
                                            <input type="file" name="identity_image" id="identity_image"
                                                class="form-control form-control-sm" placeholder="Masukkan Foto Identitas"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Room --}}
                    <div class="row" id="room-container">
                        <div class="col-md-6 card-room">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between">
                                    <h4 class="card-title mg-b-0 room-title">Kamar <span class="room-number">1</span></h4>
                                    <button class="btn btn-outline-primary btn-sm btn-add-room" type="button">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="tx-12" for="check_in">Tanggal Check-in<span
                                                        class="tx-danger">*</span></label>
                                                <input type="date" name="check_in[]"
                                                    class="form-control form-control-sm check_in"
                                                    placeholder="Masukkan Tanggal Check-in" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="tx-12" for="check_out">Tanggal Check-out<span
                                                        class="tx-danger">*</span></label>
                                                <input type="date" name="check_out[]"
                                                    class="form-control form-control-sm check_out"
                                                    placeholder="Masukkan Tanggal Check-out" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="tx-12" for="name">Tipe Kamar<span
                                                        class="tx-danger">*</span></label>
                                                <select name="room_type[]" class="form-control form-control-sm room-type"
                                                    required>
                                                    <option value="">Pilih Tipe Kamar</option>
                                                    @foreach ($data->roomType as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-price="{{ $item->base_price }}"
                                                            data-name="{{ $item->type_name }}">{{ $item->type_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="tx-12" for="email">Nomor Kamar<span
                                                        class="tx-danger">*</span></label>
                                                <select name="room_number[]"
                                                    class="form-control form-control-sm room-number" required>
                                                    <option value="">Pilih Nomor Kamar</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label class="tx-12" for="name">Layanan Tambahan</label>
                                                <select class="form-control form-control-sm select2 additional_room"
                                                    name="additional_services[]" multiple="multiple"
                                                    data-placeholder="Pilih Layanan Tambahan">
                                                    @if (count($data->additionalRoom) > 0)
                                                        @foreach ($data->additionalRoom as $add)
                                                            <option value="{{ $add->id }}"
                                                                data-price="{{ $add->price }}"
                                                                data-name="{{ $add->name }}">
                                                                {{ $add->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        {{-- Information --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title mg-b-0">Informasi Tambahan</h4>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label class="tx-12" for="name">Layanan Tambahan</label>
                                        <select class="form-control form-control-sm select2 additional_services"
                                            id="additional_services" name="additional_services" multiple="multiple">
                                            @if (count($data->generalAdd) > 0)
                                                @foreach ($data->generalAdd as $add)
                                                    <option value="{{ $add->id }}">
                                                        {{ $add->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="form-group mt-2">
                                            <label class="tx-12" for="book_reff">Sumber Reservasi<span
                                                    class="tx-danger">*</span></label>
                                            <select name="book_reff" id="book_reff" class="form-control form-control-sm"
                                                required>
                                                <option value="">Pilih Sumber Reservasi</option>
                                                <option value="direct_walkin">Direct Walk-In</option>
                                                <option value="direct_wa">Direct Whatsapp</option>
                                                <option value="ota">OTA</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2 ota_container">
                                            <label class="tx-12" for="book_reff">External Booking ID<span
                                                    class="tx-danger">*</span></label>
                                            <input type="text" name="external_booking_id" id="external_booking_id"
                                                class="form-control form-control-sm"
                                                placeholder="Masukkan External Booking ID">
                                        </div>
                                        <div class="form-group mt-2 ota_container">
                                            <label class="tx-12" for="ota_name">OTA Name<span
                                                    class="tx-danger">*</span></label>
                                            <select name="ota_name" id="ota_name" class="form-control form-control-sm">
                                                <option value="">Pilih OTA Name</option>
                                                <option value="Agoda">Agoda</option>
                                                <option value="Booking.com">Booking.com</option>
                                                <option value="Ticket.com">Ticket.com</option>
                                                <option value="Traveloka">Traveloka</option>
                                                <option value="Airbnb">Airbnb</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2 ">
                                            <label class="tx-12" for="book_reff">Catatan Tambahan</label>
                                            <textarea type="text" name="additional_notes" id="additional_notes" class="form-control form-control-sm"
                                                placeholder="Masukkan Catatan Tambahan"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Payment  --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title mg-b-0">Detail Pembayaran</h4>
                                </div>
                                <div class="card-body">
                                    <div class="rowpd-l-20 pd-r-0">


                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="tx-12" for="name">Subtotal</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="hidden" name="subtotal_value" id="subtotal_value"
                                                        value="0">
                                                    <input type="number" name="subtotal" id="subtotal"
                                                        class="form-control form-control-sm" value="0" readonly
                                                        placeholder="Masukkan Jumlah Pembayaran">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="name" class="tx-12">PPN (11%)</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group ms-4">
                                                    <input type="checkbox" name="tax" id="tax"
                                                        class="custom-control-input">
                                                    <input type="hidden" name="tax_value" id="tax_value"
                                                        value="0">
                                                    <label class="custom-control-label tx-12" for="tax">Aktifkan
                                                        PPN</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="tx-12" for="name">Voucher</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input class="form-control form-control-sm"
                                                            placeholder="Masukkan Kode Voucher" type="text"
                                                            name="voucher_code" id="voucher_code" />
                                                        <span class="input-group-btn"><button
                                                                class="btn btn-sm btn-primary" type="button">
                                                                <span
                                                                    class="input-group-btn">Periksa</span></button></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="name" class="tx-12">Jumlah Diskon</label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control form-control-sm" name="disc_type" id="disc_type">
                                                        <option value="">Pilih Tipe Diskon</option>
                                                        <option value="nominal">Nominal</option>
                                                        <option value="percentage">Persentase</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" name="discount_display" id="discount_display"
                                                        class="form-control form-control-sm input-display"
                                                        placeholder="Masukkan Jumlah Diskon">
                                                    <input type="hidden" name="discount" id="discount"
                                                        class="input-raw">
                                                    <input type="hidden" name="discount_amount_value" id="discount_amount_value"
                                                        class="input-raw">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="name" class="tx-12">Grand Total</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="hidden" name="grand_total_value" id="grand_total_value"
                                                        value="0">
                                                    <input type="number" name="grand_total" id="grand_total"
                                                        class="form-control form-control-sm"
                                                        placeholder="Masukkan Jumlah Diskon" value="0" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="tx-12" for="name">Jumlah Pembayaran<span
                                                        class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" name="payment_amount_display" id="payment_amount_display"
                                                        class="form-control form-control-sm input-display" value=""
                                                        placeholder="Masukkan Jumlah Pembayaran" required>
                                                    <input type="hidden" class="input-raw" name="payment_amount" id="payment_amount"
                                                        value="" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="tx-12" for="name">Jumlah DP</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" name="dp_amount_masking" id="dp_amount_masking"
                                                        class="form-control form-control-sm input-display"
                                                        value="{{ old('dp_amount') }}" placeholder="Masukkan Jumlah DP">
                                                    <input type="hidden" class="input-raw" name="dp_amount" id="dp_amount"
                                                        value="{{ old('dp_amount') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="tx-12" for="name">Metode Pembayaran<span
                                                        class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <select name="payment_method" id="payment_method"
                                                        class="form-control form-control-sm" required>
                                                        <option value="">Pilih Metode Pembayaran</option>
                                                        <option value="Bank Transfer">Transfer Bank</option>
                                                        <option value="Cash">Tunai</option>
                                                        <option value="QRIS">QRIS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="name" class="tx-12">Tanggal Pembayaran<span
                                                        class="tx-danger">*</span></label>

                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="date" name="payment_date" id="payment_date"
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="Masukkan Tanggal Pembayaran" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @include('pages.booking.confirm')
                <div class="form-group has-success col-12 mb-0 mt-3 pe-4 d-flex justify-content-end">
                    <div class="d-flex justify-content-end"></div>
                    <div>
                        <button type="button" class="btn btn-primary d-block" id="next-booking">PROSES</button>
                        <div class="d-flex justify-content-end">
                            <button type="button" id="back-process" class="btn btn-secondary d-none me-2"
                                id="back-process">Kembali</button>
                            <button type="submit" class="btn btn-primary d-none"
                                id="save-booking-submit">SIMPAN</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    {{-- @include('pages.booking.form-input') --}}

@endsection
@section('script')
    <script src="{{ url('assets') }}/js/form-elements.js"></script>
    <script>
        window.onload = function() {
            // Inisialisasi Select2
            if ($('.select2').length > 0) {
                $('.select2').select2();
            }
        };
        var guest = {};
        var rooms = [];
        var payment = {};

        $(document).ready(function() {
            toastr.options = {
                "positionClass": "toast-top-center",
                "timeOut": "3000", // Durasi tampil (3 detik)
                "extendedTimeOut": "1000",
                "fadeIn": 300,
                "fadeOut": 1000,
                "progressBar": true
            };
            $('.select2').select2();

            $('.ota_container').hide();

            $('#book_reff').on('change', function() {
                if ($(this).val() == 'ota') {
                    $('.ota_container').show();
                    $('#external_booking_id').prop('required', true);
                    $('#ota_name').prop('required', true);
                } else {
                    $('.ota_container').hide();
                    $('#external_booking_id').prop('required', false);
                    $('#ota_name').prop('required', false);
                }
            });

            $(document).on('change', '.room-type', function() {

                let typeId = $(this).val(); // Ambil value Tipe Kamar yang dipilih
                let checkIn = $(this).closest('.row').find('.check_in').val();
                let checkOut = $(this).closest('.row').find('.check_out').val();

                // Cari dropdown Nomor Kamar yang berada di baris (.row) yang sama
                let $roomNumberDropdown = $(this).closest('.row').find('.room-number');

                // Kosongkan opsi nomor kamar sebelumnya dan beri teks loading sementara
                $roomNumberDropdown.empty().append('<option value="">Loading...</option>');

                // Jika Tipe Kamar dipilih (tidak kosong)
                if (typeId) {
                    $.ajax({
                        // GANTI URL INI sesuai dengan endpoint API Laravel Anda
                        url: '/api/get-room-numbers/' + typeId,
                        data: {
                            check_in: checkIn,
                            check_out: checkOut
                        },
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {

                            $roomNumberDropdown.empty().append(
                                '<option value="">Pilih Nomor Kamar</option>');

                            $.each(response.data, function(key, room) {
                                $roomNumberDropdown.append(
                                    '<option value="' + room.id +
                                    '" data-number="' + room.room_number + '">' +
                                    room
                                    .room_number + '</option>'
                                );
                            });
                            if ($roomNumberDropdown.hasClass('select2-hidden-accessible')) {
                                $roomNumberDropdown.trigger('change');
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr);

                            console.error("Terjadi kesalahan saat mengambil nomor kamar.");
                            $roomNumberDropdown.empty().append(
                                '<option value="">Pilih Nomor Kamar</option>');
                        }
                    });
                } else {
                    // Jika user memilih opsi default "Pilih Tipe Kamar"
                    $roomNumberDropdown.empty().append('<option value="">Pilih Nomor Kamar</option>');
                }
            });

            $(document).on('click', '.btn-add-room', function(e) {
                e.preventDefault();
                let $firstCard = $('.card-room').first();
                let $clone = $firstCard.clone();

                // --- MULAI PROSES PEMBERSIHAN JEJAK SELECT2 DI HASIL CLONE ---

                // a. Hapus elemen span/div tambahan yang dibuat oleh Select2 sebelumnya
                $clone.find('.select2-container').remove();

                // b. Bersihkan tag <select> dari atribut bawaan Select2 agar kembali jadi select biasa
                $clone.find('select').each(function() {
                    $(this).removeClass('select2-hidden-accessible');
                    $(this).removeAttr('data-select2-id tabindex aria-hidden');
                });

                // c. Bersihkan semua atribut ID select2 yang menempel pada <option> atau elemen lain
                $clone.find('[data-select2-id]').removeAttr('data-select2-id');

                // --- SELESAI PROSES PEMBERSIHAN ---

                // Bersihkan nilai input form agar kosong
                $clone.find('input').val('');
                $clone.find('select:not([multiple])').prop('selectedIndex',
                    0); // Dropdown biasa kembali ke default
                $clone.find('select[multiple]').val([]).trigger('change'); // Kosongkan khusus multi-select

                // Ubah tombol Tambah menjadi Hapus
                let $btn = $clone.find('.card-header button');
                $btn.removeClass('btn-outline-primary btn-add-room')
                    .addClass('btn-outline-danger btn-remove-room')
                    .html('<i class="fas fa-trash"></i> Hapus');

                // Masukkan elemen baru ke dalam Container
                $('#room-container').append($clone);

                // Inisialisasi ulang Select2 HANYA pada elemen yang baru ditambahkan
                $clone.find('.select2').select2();

                // Update penomoran
                updateRoomNumbers();
            });

            $(document).on('change', '.check_in', function() {
                let checkInVal = $(this).val();
                let $row = $(this).closest('.card-room');
                let $checkOutInput = $row.find('.check_out');

                if (checkInVal) {
                    let checkInDate = new Date(checkInVal);
                    checkInDate.setDate(checkInDate.getDate() + 1);
                    let minCheckOut = checkInDate.toISOString().split('T')[0];
                    $checkOutInput.attr('min', minCheckOut);
                    if ($checkOutInput.val() && $checkOutInput.val() < minCheckOut) {
                        $checkOutInput.val('');
                        toastr.warning(
                            'Tanggal check-out otomatis disesuaikan karena minimal 1 hari setelah check-in.',
                            'Informasi');
                    }
                } else {

                    $checkOutInput.removeAttr('min');
                }
            });

            $('#next-booking').on('click', function(e) {
                if (!validateInput()) {
                    return false;
                }
                // Jika semua validasi lolos
                $('#booking-container').removeClass('d-block').addClass('d-none');
                $('#back-process').removeClass('d-none').addClass('d-block');
                $('#save-booking-submit').removeClass('d-none').addClass('d-block');
                $('#next-booking').removeClass('d-block').addClass('d-none');
                $('#confirm-container').removeClass('d-none').addClass(
                    'd-block'); // Tampilkan detail pembayaran

                processBooking();
            });

            $('#back-process').on('click', function() {
                $('#confirm-container').removeClass('d-block').addClass('d-none');
                $('#back-process').removeClass('d-block').addClass('d-none');
                $('#save-booking-submit').removeClass('d-block').addClass('d-none');
                $('#next-booking').removeClass('d-none').addClass('d-block');
                $('#booking-container').removeClass('d-none').addClass('d-block');
            });

            $(document).on('change',
                '.room-type, .check_in, .check_out, .additional_room',
                function() {

                    calculateTotalPayment();
                });
        });

        $(document).on('click', '.btn-remove-room', function(e) {
            e.preventDefault();
            // Hapus elemen kamar yang tombol hapusnya diklik
            $(this).closest('.card-room').remove();

            // Update penomoran
            updateRoomNumbers();
            setTimeout(function() {
                calculateTotalPayment();
            }, 100);
        });

        $(document).on('change', '#additional_services', function() {
            calculateTotalPayment();
        });
        $(document).on('change', '#tax', function() {
            if ($(this).val() == 1) {
                $('#tax_value').val(11);
            } else {
                $('#tax_value').val(0);
            }
            calculateTotalPayment();
        });

        $(document).on('change', '#discount_display', function() {
            $('#payment_amount_display').val(0);
            $('#payment_amount').val(0);
            calculateTotalPayment();
        });
        $(document).on('change', '#payment_amount_display', function() {
            $('#discount_display').val(0);
            $('#discount').val(0);
            calculateTotalPayment();
        });

        $(document).ready(function() {
            let today = new Date().toISOString().split('T')[0];

            $('.check_in').attr('min', today);

            $(document).on('click', '.btn-add', function() {
                let $lastCard = $('.card-room').last();
                $lastCard.find('.check_in').attr('min', today);
            });
        });

        function updateRoomNumbers() {
            $('.card-room').each(function(index) {
                let number = index+1;
                $(this).find('.room-number').text(number);
                $(this).find('.additional_room').prop('name', 'additional_room-'+index+'[]');
                
            });
        }

        function calculateTotalPayment() {
            let subtotal = 0;
            let grandTotal = 0;

            $('.card-room').each(function() {
                let $roomCard = $(this);

                let roomPrice = parseFloat($roomCard.find('.room-type option:selected').data('price')) || 0;

                let checkin = $roomCard.find('.check_in').val();
                let checkout = $roomCard.find('.check_out').val();

                let totalMalam = 0;
                if (checkin && checkout) {
                    let diffTime = new Date(checkout).getTime() - new Date(checkin).getTime();
                    totalMalam = diffTime / (1000 * 3600 * 24);
                }

                let subtotalRoom = roomPrice * totalMalam;


                let subtotalAddRoom = 0;
                $roomCard.find('.additional_room option:selected').each(function() {
                    let addPrice = parseFloat($(this).data('price')) || 0;
                    let subtotalPriceAdd = addPrice * totalMalam
                    subtotalAddRoom += subtotalPriceAdd;
                });

                subtotal += (subtotalRoom + subtotalAddRoom);
            });

            $('#additional_services option:selected').each(function() {
                let generalPrice = parseFloat($(this).data('price')) || 0;
                subtotal += generalPrice;
            });
            let ppnAmount = 0;
            if ($('#tax').is(':checked')) {
                ppnAmount = subtotal * 0.11;
                $('#tax_value').val(ppnAmount);
            }

            let discType = $("#disc_type").val();
            let discValue = $("#discount").val();
            let discDisplayValue = $("#discount_display").val();
            let discountAmount = 0;
            if (discValue != '' && discType != '') {
                if (discType == 'percentage') {
                    discountAmount = subtotal * (discValue / 100);
                } else if (discType == 'nominal') {
                    discountAmount = discValue;
                }
            }
            console.log(`amount`,discountAmount);
            
            grandTotal = subtotal + ppnAmount - discountAmount;
            $('#subtotal').val(rupiah(subtotal));
            $('#subtotal_value').val(subtotal);
            $('#grand_total').val(rupiah(grandTotal));
            $('#grand_total_value').val(grandTotal);
            $('#discount_amount_value').val(discountAmount);
            $('#total_discount').val(rupiah(discountAmount));
            // $('#total_payment').val(rupiah(grandTotal));
        }

        function handleVoucher() {}

        function processBooking() {

            guest = {
                name: $('#guest_name').val(),
                no_hp: $('#phone').val(),
                email: $('#guest_email').val(),
                address: $('#address').val(),
            };

            rooms = [];

            $('.card-room').each(function(index) {
                let additional = [];
                $(this).find('.additional_room').find('option:selected').each(function() {
                    additional.push({
                        name: $(this).data('name'),
                        price: parseFloat($(this).data('price')) || 0,
                    });
                });

                let date = {
                    check_in: $(this).find('.check_in').val(),
                    check_out: $(this).find('.check_out').val()
                }

                let timeDifference = new Date(date.check_out).getTime() - new Date(date.check_in).getTime();
                let dayDifference = timeDifference / (1000 * 3600 * 24);

                rooms.push({
                    room_type: $(this).find('.room-type').find('option:selected').data('name'),
                    room_number: $(this).find('.room-number').find('option:selected').data('number'),
                    night_price: parseFloat($(this).find('.room-type').find('option:selected').data(
                        'price')) || 0,
                    check_in: date.check_in,
                    check_out: date.check_out,
                    total_night: isNaN(dayDifference) ? 0 : dayDifference,
                    additional: additional,
                });
            });

            // Ambil Additional General (Layanan tambahan umum di luar kamar)
            let generalAdditional = [];
            $('#general_additional').find('option:selected').each(function() {
                generalAdditional.push({
                    name: $(this).data('name'),
                    price: parseFloat($(this).data('price')) || 0,
                });
            });

            let bookData = [];
            let totalPrice = 0;
            let itemBooking = '';

            // 1. Looping untuk Kamar & Additional Room-nya
            for (const rm in rooms) {
                let roomData = rooms[rm];
                let subTotalPrice = roomData.total_night * roomData.night_price;

                // Render baris Kamar Utama
                itemBooking += `
                <tr>
                    <td>Kamar</td>
                    <td>${roomData.room_type} (${roomData.room_number})</td>
                    <td>${rupiah(roomData.night_price)}</td>
                    <td>${formatTanggal(roomData.check_in)}</td>
                    <td>${formatTanggal(roomData.check_out)}</td>
                    <td class="tx-center">${roomData.total_night}</td>
                    <td class="tx-right">${rupiah(subTotalPrice)}</td>
                </tr>
            `;
                totalPrice += subTotalPrice;

                // Jika kamar ini memiliki Additional Room, render baris tambahannya di bawahnya
                if (roomData.additional.length > 0) {
                    for (const addIdx in roomData.additional) {
                        let addItem = roomData.additional[addIdx];
                        itemBooking += `
                        <tr>
                            <td>Additional (Room)</td>
                            <td>${addItem.name} (${roomData.room_number})</td>
                            <td>${rupiah(addItem.price)}</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="tx-center">-</td>
                            <td class="tx-right">${rupiah(addItem.price)}</td>
                        </tr>
                    `;
                        totalPrice += addItem.price;
                    }
                }

                bookData.push(roomData);
            }

            // 2. Looping untuk Additional General (Layanan Umum)
            if (generalAdditional.length > 0) {
                for (const genIdx in generalAdditional) {
                    let genItem = generalAdditional[genIdx];
                    itemBooking += `
                    <tr>
                        <td>Additional</td>
                        <td>${genItem.name}</td>
                        <td>${rupiah(genItem.price)}</td>
                        <td>-</td>
                        <td>-</td>
                        <td class="tx-center">-</td>
                        <td class="tx-right">${rupiah(genItem.price)}</td>
                    </tr>
                `;
                    totalPrice += genItem.price;
                }
            }

            let additionalNotes = $('#additional_notes').val();
            $('#dt-booking').html(itemBooking);
            $('#total_price').val(totalPrice);
            $('#cfrm_guest_name').html(guest.name);
            $('#cfrm_guest_telp').html(guest.no_hp);
            $('#cfrm_guest_email').html(guest.email);
            $('#cfrm_guest_address').html(guest.address);
            $('#additional_notes_cfrm').html(additionalNotes);

            //payment information
            let ppnAmount = 0;
            if ($('#tax').is(':checked')) {
                ppnAmount = totalPrice * 0.11;
            }
            let discount = $('#discount_amount_value').val();
            let downPayment = $('#dp_amount').val();
            let grandTotal = (totalPrice + ppnAmount) - downPayment - discount;
            let totalPayment = (totalPrice + ppnAmount) - discount;
            let payment_date = $('#payment_date').val();
            $('#total_sub').html(rupiah(totalPrice));
            $('#total_tax').html(rupiah(ppnAmount));
            $('#total_dp').html(rupiah(downPayment));
            $('#total_remaining').html(rupiah(grandTotal));
            $('#total_discount').html(rupiah(discount));
            $('#total_all').html(rupiah(totalPayment));
            $('#cfrm_payment_date').html(formatTanggal(payment_date));

            let payment = {
                total_price: totalPrice,
                payment_method: $('#payment_method').val(),
            };

        }

        function rupiah(angka) {
            if (!angka && angka !== 0) return 'Rp 0';

            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0, // Ubah ke 2 jika ingin menampilkan desimal (sen)
                maximumFractionDigits: 0
            }).format(angka);
        }

        function formatTanggal(tanggal) {
            if (!tanggal) return '-';

            let dateObj = new Date(tanggal);

            // Validasi jika tanggal tidak valid
            if (isNaN(dateObj.getTime())) return 'Tanggal tidak valid';

            // Format: 09 September 2026 (menggunakan locale Indonesia)
            return dateObj.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function validateInput() {
            if (validateRoom() == false) {
                return false;
            }
            var guestName = $('#guest_name').val();
            if (!guestName) {
                toastr.warning('Masukkan nama tamu');
                return false;
            }
            var guestTelp = $('#phone').val();
            if (!guestTelp) {
                toastr.warning('Masukkan nomor telepon');
                return false;
            }
            var guestEmail = $('#guest_email').val();
            if (!guestEmail) {
                toastr.warning('Masukkan email');
                return false;
            }
            var guestAddress = $('#address').val();
            if (!guestAddress) {
                toastr.warning('Masukkan alamat');
                return false;
            }
            var idType = $('#identity_type').val();
            if (!idType) {
                toastr.warning('Pilih tipe identitas');
                return false;
            }
            var idNo = $('#identity_number').val();
            if (!idNo) {
                toastr.warning('Masukkan nomor identitas');
                return false;
            }
            var paymentDate = $('#payment_date').val();
            if (!paymentDate) {
                toastr.warning('Masukkan tanggal pembayaran');
                return false;
            }
            var paymentMethod = $('#payment_method').val();
            if (!paymentMethod) {
                toastr.warning('Masukkan metode pembayaran');
                return false;
            }
            var bookReff = $('#book_reff').val();
            if (!bookReff) {
                toastr.warning('Pilih sumber reservasi');
                return false;
            }
            var otaExternalBookingId = $('#external_booking_id').val();

            if (bookReff === 'ota' && !otaExternalBookingId) {
                toastr.warning('Masukkan nomor booking OTA');
                return false;
            }
            var otaName = $('#ota_name').val();
            if (bookReff === 'ota' && !otaName) {
                toastr.warning('Masukkan nama OTA');
                return false;
            }
            var paymentAmount = parseInt($('#payment_amount').val());
            var dpAmount = parseInt($('#dp_amount').val());
            if (paymentAmount == '' && dpAmount == '') {
                toastr.warning('Masukkan jumlah pembayaran atau down payment');
                return false;
            }

            return true;
        }

        function validateRoom() {
            let isValid = true;
            let today = new Date();
            today.setHours(0, 0, 0, 0); // Reset jam ke 00:00:00 agar perbandingan tanggal akurat

            $('.card-room').each(function(index) {
                let roomNum = index + 1; // Label kamar (Kamar 1, Kamar 2, dst.)
                let card = $(this);

                let roomTypeSelect = card.find('.room-type');
                let roomNumberSelect = card.find('.room-number');
                let checkInInput = card.find('.check_in');
                let checkOutInput = card.find('.check_out');

                let roomTypeId = roomTypeSelect.val();
                let roomNumberVal = roomNumberSelect.find('option:selected').val();
                let checkInVal = checkInInput.val();
                let checkOutVal = checkOutInput.val();

                if (!roomTypeId) {
                    toastr.warning(`Silakan pilih Tipe Kamar pada Kamar ${roomNum}!`, 'Peringatan');
                    roomTypeSelect.focus();
                    isValid = false;
                    return false;
                }

                if (!roomNumberVal) {
                    toastr.warning(`Silakan pilih Nomor Kamar pada Kamar ${roomNum}!`, 'Peringatan');
                    roomNumberSelect.focus();
                    isValid = false;
                    return false;
                }

                if (!checkInVal) {
                    toastr.warning(`Tanggal Check-in pada Kamar ${roomNum} belum diisi!`, 'Peringatan');
                    checkInInput.focus();
                    isValid = false;
                    return false;
                }

                if (!checkOutVal) {
                    toastr.warning(`Tanggal Check-out pada Kamar ${roomNum} belum diisi!`, 'Peringatan');
                    checkOutInput.focus();
                    isValid = false;
                    return false;
                }

                let checkInDate = new Date(checkInVal);
                let checkOutDate = new Date(checkOutVal);

                if (checkInDate < today) {
                    toastr.warning(`Tanggal Check-in pada Kamar ${roomNum} tidak boleh lewat dari hari ini!`,
                        'Peringatan');
                    checkInInput.focus();
                    isValid = false;
                    return false;
                }

                if (checkOutDate <= checkInDate) {
                    toastr.warning(
                        `Tanggal Check-out pada Kamar ${roomNum} harus lebih besar dari tanggal Check-in!`,
                        'Peringatan');
                    checkOutInput.focus();
                    isValid = false;
                    return false;
                }

            });

            if (!isValid) {
                return;
            }
        }

        $(document).on('input', '.input-number', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(value);
        });

        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
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
    </script>
@endsection
