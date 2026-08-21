@extends('template.app')

@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">Dashboard
                </span><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->subtitle }} / </span>
                 <h6 class="content-title mb-0 my-auto">{{ $data->title }}</h6>

            </div>

        </div>

    </div>

    <x-alert />
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h1 class="invoice-title">BKG-202608100001</h1>
                            <div class="billed-from">
                                <h6>Ezzy Homestay.</h6>
                                <p>Gebang, Jember Jawa Timur<br>
                                    Tel No: 08123456789<br>
                                    Email: ezzyhomestay@gmail.com</p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class="row mg-t-20">
                            <div class="col-md">
                                <label class="tx-gray-600">Guest</label>
                                <div class="billed-to">
                                    <h6>[Nama Tamu]</h6>
                                    <p>[Nomor Telepon]<br>
                                       [Email]</p>
                                </div>
                            </div>
                            <div class="col-md">
                                <label class="tx-gray-600">Informasi Pemesanan Kamar</label>
                                <p class="invoice-info-row"><span>Nomor Pemesanan</span> <span>BKG-202608100001</span></p>
                                <p class="invoice-info-row"><span>Tanggal Pemesanan</span> <span>17 Agustus 2026</span></p>
                                <p class="invoice-info-row"><span>Tanggal Pembayaran:</span> <span>16 Agustus 2026</span></p>
                                <p class="invoice-info-row"><span>Petugas </span> <span>Zainul Anshori</span></p>
                            </div>
                        </div>
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th class="wd-10p">Jenis Layanan</th>
                                        <th class="wd-20p">Tipe</th>
                                        <th class="wd-10p">Nomor Kamar</th>
                                        <th class="wd-10p">Tanggal Check-in</th>
                                        <th class="wd-15p">Tanggal Check-out</th>
                                        <th class="wd-10p tx-center">Jumlah Tamu</th>
                                        <th class="wd-5p tx-center">Jumlah</th>
                                        <th class="tx-right">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kamar</td>
                                        <td>Deluxe</td>
                                        <td class="tx-center">101</td>
                                        <td class="tx-left">17 Agustus 2026</td>
                                        <td class="tx-left">18 Agustus 2026</td>
                                        <td class="tx-center">2</td>
                                        <td class="tx-center">1</td>
                                        <td class="tx-right">Rp. 300.000</td>
                                    </tr>
                                    <tr>
                                        <td>Kamar</td>
                                        <td>Deluxe</td>
                                        <td class="tx-center">102</td>
                                        <td class="tx-left">17 Agustus 2026</td>
                                        <td class="tx-left">18 Agustus 2026</td>
                                        <td class="tx-center">2</td>
                                        <td class="tx-center">1</td>
                                        <td class="tx-right">Rp. 300.000</td>
                                    </tr>
                                    <tr>
                                        <td>Additional</td>
                                        <td>Extra Bed, Breakfast</td>
                                        <td class="tx-center">101,102</td>
                                        <td class="tx-left">-</td>
                                        <td class="tx-left">-</td>
                                        <td class="tx-center">4</td>
                                        <td class="tx-center">2</td>
                                        <td class="tx-right">Rp. 100.000</td>
                                    </tr>

                                    <tr>
                                        <td class="valign-middle" colspan="5" rowspan="4">
                                            <div class="invoice-notes">
                                                <label class="main-content-label tx-13">Notes</label>
                                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                                    accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab
                                                    illo inventore veritatis et quasi architecto beatae vitae dicta sunt
                                                    explicabo.</p>
                                            </div><!-- invoice-notes -->
                                        </td>
                                        <td class="tx-right">Sub-Total</td>
                                        <td class="tx-right" colspan="2">Rp. 700.000</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-right">Pajak (11%)</td>
                                        <td class="tx-right" colspan="2">Rp. 77.000</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-right">Diskon</td>
                                        <td class="tx-right" colspan="2">Rp. 50.000</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-right tx-uppercase tx-bold tx-inverse">Total</td>
                                        <td class="tx-right" colspan="2">
                                            <h4 class="tx-primary tx-bold">Rp. 727.000</h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--  -->

                        <a href="#" class="btn btn-success float-end mt-3">
                            <i class="mdi mdi-telegram me-1"></i>Simpan
                        </a>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
@endsection
