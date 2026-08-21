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
                <div class="row">
                    @for ($i = 1; $i < 4; $i++)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between">
                                    <h4 class="card-title mg-b-0">Kamar {{ $i }}</h4>
                                    <button class="btn {{ $i == 3 ? 'btn-outline-primary' : 'btn-outline-danger' }} btn-sm"
                                        type="button"><i class="fas {{ $i == 3 ? 'fa-plus' : 'fa-trash' }}"></i>
                                        {{ $i == 3 ? 'Tambah' : 'Hapus' }}</button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Tipe Kamar</label>
                                                <select name="identity_type" id="identity_type" class="form-control"
                                                    required>
                                                    <option value="">Pilih Tipe Kamar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Nomor Kamar</label>
                                                <select name="identity_type" id="identity_type" class="form-control"
                                                    required>
                                                    <option value="">Pilih Nomor Kamar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">Jumlah Tamu</label>
                                                <input type="number" name="adults" id="adults" class="form-control"
                                                    placeholder="Masukkan Jumlah Tamu" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Tanggal Check-in</label>
                                                <input type="date" name="check-in" id="check-in" class="form-control"
                                                    placeholder="Masukkan Tanggal Check-in" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Tanggal Check-out</label>
                                                <input type="date" name="check-out" id="check-out"
                                                    class="form-control" placeholder="Masukkan Tanggal Check-out"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label for="name">Layanan Tambahan</label>
                                            <select class="form-control select2" name="additional_services[]"
                                                multiple="multiple">
                                                <option selected value="Extra Bed">Extra Bed</option>
                                                <option value="Breakfast">Breakfast</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    @endfor
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
                                        <option selected value="Extra Bed">Extra Bed</option>
                                        <option value="Breakfast">Breakfast</option>
                                    </select>
                                    <div class="form-group mt-2">
                                        <label for="identity_type">Sumber Reservasi</label>
                                        <select name="identity_type" id="identity_type" class="form-control" required>
                                            <option value="">Pilih Sumber Reservasi</option>
                                            <option value="Walk-In">Walk-In</option>
                                            <option value="Direct">Direct</option>
                                            <option value="OTA">OTA</option>
                                        </select>
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
                                                <select name="identity_type" id="identity_type" class="form-control"
                                                    required>
                                                    <option value="">Pilih Metode Pembayaran</option>
                                                    <option value="Transfer Bank">Transfer Bank</option>
                                                    <option value="Tunai">Tunai</option>
                                                    <option value="Kartu Kredit">Kartu Kredit</option>
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
                                                        type="text" />
                                                    <span class="input-group-btn"><button class="btn btn-primary"
                                                            type="button">
                                                            <span
                                                                class="input-group-btn">Periksa</span></button></span>
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
                                                    class="form-control" placeholder="Masukkan Jumlah Diskon"
                                                    required>
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
                                                    class="form-control" placeholder="Masukkan Catatan Tambahan"
                                                    required>
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
            $('.select2').select2();
        };
    </script>
@endsection
