@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">Dashboard/
                </span><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->subtitle }} </span>
                <h5 class="content-title mb-0 my-auto">{{ $data->title }}</h5>

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
            <form action="" method="POST" class="form-horizontal row " enctype="multipart/form-data">
                @csrf
                {{-- @include('pages.booking.form-input') --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title mg-b-0">Data Pemesan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col form-group">
                                        <label for="name">Nama Pemesan</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Masukkan Nama Pemesan" required>
                                    </div>
                                    <div class="col form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Masukkan Email Pemesan" required>
                                    </div>
                                    <div class="col form-group">
                                        <label for="phone">Telepon/Whatsapp</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="Masukkan Telepon/Whatsapp" required>
                                    </div>
                                    <div class="col form-group">
                                        <label for="identity_type">Tipe Identitas</label>
                                        <select name="identity_type" id="identity_type" class="form-control" required>
                                            <option value="">Pilih Tipe Identitas</option>
                                            <option value="KTP">KTP</option>
                                            <option value="SIM">SIM</option>
                                            <option value="Passport">Passport</option>
                                        </select>
                                    </div>
                                    <div class="col form-group">
                                        <label for="identity_number">Nomor Identitas</label>
                                        <input type="text" name="identity_number" id="identity_number"
                                            class="form-control" placeholder="Masukkan Nomor Identitas" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Tipe Kamar</label>
                                            <select name="room_type[]" class="form-control room-type" required>
                                                <option value="">Pilih Tipe Kamar</option>
                                                @foreach ($data->roomType as $item)
                                                    <option value="{{ $item->id }}">{{ $item->type_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Nomor Kamar</label>
                                            <select name="room_number[]" class="form-control room-number" required>
                                                <option value="">Pilih Nomor Kamar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Jumlah Tamu</label>
                                            <input type="number" name="total_guest[]" id="total_guest" class="form-control"
                                                placeholder="Masukkan Jumlah Tamu" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="check_in">Tanggal Check-in</label>
                                            <input type="date" name="check_in[]" id="check_in" class="form-control"
                                                placeholder="Masukkan Tanggal Check-in" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="check_out">Tanggal Check-out</label>
                                            <input type="date" name="check_out[]" id="check_out" class="form-control"
                                                placeholder="Masukkan Tanggal Check-out" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label for="name">Layanan Tambahan</label>
                                            <select class="form-control select2" name="additional_services[]"
                                                multiple="multiple" data-placeholder="Pilih Layanan Tambahan">
                                                @if (count($data->additionalRoom) > 0)
                                                    @foreach ($data->additionalRoom as $add)
                                                        <option value="{{ $add->id }}">
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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title mg-b-0">Informasi Tambahan</h4>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="name">Layanan Tambahan</label>
                                    <select class="form-control select2" name="additional_services[]"
                                        multiple="multiple">
                                        @if (count($data->generalAdd) > 0)
                                            @foreach ($data->generalAdd as $add)
                                                <option value="{{ $add->id }}">
                                                    {{ $add->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="form-group mt-2">
                                        <label for="book_reff">Sumber Reservasi</label>
                                        <select name="book_reff" id="book_reff" class="form-control" required>
                                            <option value="">Pilih Sumber Reservasi</option>
                                            <option value="direct_walkin">Walk-In</option>
                                            <option value="direct_wa">Direct</option>
                                            <option value="ota">OTA</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2 ota_container">
                                        <label for="book_reff">External Booking ID</label>
                                        <input type="text" name="external_booking_id" id="external_booking_id"
                                            class="form-control" placeholder="Masukkan External Booking ID" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title mg-b-0">Detail Pembayaran</h4>
                            </div>
                            <div class="card-body">
                                <div class="rowpd-l-20 pd-r-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="name">Metode Pembayaran</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <select name="payment_method" id="payment_method" class="form-control"
                                                    required>
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
                                            <label for="name">Voucher</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input class="form-control" placeholder="Masukkan Kode Voucher"
                                                        type="text" name="voucher_code" id="voucher_code" />
                                                    <span class="input-group-btn"><button class="btn btn-primary"
                                                            type="button">
                                                            <span class="input-group-btn">Periksa</span></button></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="name">Jumlah Pembayaran</label>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="number" name="payment_amount" id="payment_amount"
                                                    class="form-control" placeholder="Masukkan Jumlah Pembayaran"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="name">Jumlah Diskon</label>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="number" name="discount" id="discount"
                                                    class="form-control" placeholder="Masukkan Jumlah Diskon" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="name">Tanggal Pembayaran</label>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="date" name="payment_date" id="payment_date"
                                                    class="form-control" placeholder="Masukkan Tanggal Pembayaran"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="name">Catatan Tambahan</label>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="text" name="additional_notes" id="additional_notes"
                                                    class="form-control" placeholder="Masukkan Catatan Tambahan" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group has-success col-12 mb-0 mt-3 d-flex justify-content-end">
                        <div class="d-flex justify-content-end"></div>
                        <div>

                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
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

        $(document).ready(function() {

            $('.select2').select2();

            $('.ota_container').hide();

            $('#book_reff').on('change', function() {
                if ($(this).val() == 'ota') {
                    $('.ota_container').show();
                } else {
                    $('.ota_container').hide();
                }
            });

            $(document).on('change', '.room-type', function() {

                let typeId = $(this).val(); // Ambil value Tipe Kamar yang dipilih

                // Cari dropdown Nomor Kamar yang berada di baris (.row) yang sama
                let $roomNumberDropdown = $(this).closest('.row').find('.room-number');

                // Kosongkan opsi nomor kamar sebelumnya dan beri teks loading sementara
                $roomNumberDropdown.empty().append('<option value="">Loading...</option>');

                // Jika Tipe Kamar dipilih (tidak kosong)
                if (typeId) {
                    $.ajax({
                        // GANTI URL INI sesuai dengan endpoint API Laravel Anda
                        url: '/api/get-room-numbers/' + typeId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {

                            // Kembalikan ke teks default
                            $roomNumberDropdown.empty().append(
                                '<option value="">Pilih Nomor Kamar</option>');

                            // Looping data dari API dan masukkan sebagai tag <option>
                            // Asumsi struktur JSON dari API: [{ "id": 1, "room_number": "101" }, ...]
                            $.each(response.data, function(key, room) {
                                $roomNumberDropdown.append(
                                    '<option value="' + room.id + '">' + room
                                    .room_number + '</option>'
                                );
                            });

                            // (Opsional) Jika pakai Select2, update tampilannya
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
                e.preventDefault(); // Mencegah form tersubmit tidak sengaja

                // Ambil elemen kamar pertama sebagai cetakan
                let $firstCard = $('.card-room').first();

                // Clone elemen tanpa mengcopy event listener bawaannya
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
        });


        // Event ketika tombol Hapus diklik
        // Menggunakan event delegation $(document).on(...) agar elemen dinamis bisa terdeteksi
        $(document).on('click', '.btn-remove-room', function(e) {
            e.preventDefault();
            // Hapus elemen kamar yang tombol hapusnya diklik
            $(this).closest('.card-room').remove();

            // Update penomoran
            updateRoomNumbers();
        });

        // Fungsi untuk mengurutkan ulang angka pada judul "Kamar 1, Kamar 2, dst"
        function updateRoomNumbers() {
            $('.card-room').each(function(index) {
                $(this).find('.room-number').text(index + 1);
            });
        }
    </script>
@endsection
