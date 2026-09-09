@php
    Carbon\Carbon::setLocale('id_ID');
@endphp
<div class="col-md-12 col-xl-12 row row-sm px-0 d-none" id="confirm-container">

    <div class=" main-content-body-invoice">
        <div class="card card-invoice">
            <div class="card-body p-0">
                <div class="invoice-header">
                    <h1 class="invoice-title">{{ $data->bookcode }}</h1>
                    <div class="billed-from">
                        <h6>Ezzy Homestay.</h6>
                        <p>Jl. Teratai No.51, Kec. Kaliwates,<br>Kabupaten Jember, Jawa Timur 68133<br>
                            Tel No: +62 823-7454-7179<br>
                            Email: ezzyhomestay@gmail.com</p>
                    </div><!-- billed-from -->
                </div><!-- invoice-header -->
                <div class="row mg-t-20">
                    <div class="col-md">
                        <label class="tx-gray-600">Tamu</label>
                        <div class="billed-to">
                            <h6 id="cfrm_guest_name"></h6>
                            <p id="cfrm_guest_telp"></p>
                            <p id="cfrm_guest_email"></p>
                            <p id="cfrm_guest_address"></p>
                        </div>
                    </div>
                    <div class="col-md">
                        <label class="tx-gray-600">Informasi Pemesanan Kamar</label>
                        <p class="invoice-info-row"><span>Nomor Pemesanan</span> <span
                                id="cfrm_booking_code">{{ $data->bookcode }}</span></p>
                        <p class="invoice-info-row"><span>Tanggal Pemesanan</span> <span
                                id="cfrm_booking_date">{{ Carbon\Carbon::parse(now())->format('d F Y') }}</span></p>
                        <p class="invoice-info-row"><span>Tanggal Pembayaran:</span> <span
                                id="cfrm_payment_date"></span>
                        </p>
                        <p class="invoice-info-row"><span>Petugas </span> <span>{{ Auth::user()->name }}</span></p>
                    </div>
                </div>
                <div class="table-responsive mg-t-40">
                    <table class="table table-invoice border text-md-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-10p">Jenis Layanan</th>
                                <th class="wd-20p">Item/Tipe Kamar</th>
                                <th class="wd-10p">Harga</th>
                                <th class="wd-10p">Tanggal Check-in</th>
                                <th class="wd-13p">Tanggal Check-out</th>
                                <th class="wd-10p tx-center">Jumlah Malam</th>
                                <th class="tx-right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="dt-booking">


                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-invoice border text-md-nowrap mb-0">
                        <tbody id="dt-price">

                            <tr>
                                <td class="valign-middle" colspan="5" rowspan="6">
                                    <div class="invoice-notes">
                                        <label class="main-content-label tx-13">Notes</label>
                                        <p id="additional_notes_cfrm" class="tx-12" style="color: #000;"></p>
                                    </div><!-- invoice-notes -->
                                </td>
                                <td class="tx-right wd-10p" colspan="2">Sub-Total</td>
                                <td class="tx-right wd-10p" id="total_sub"></td>
                            </tr>
                            <tr>
                                <td class="tx-right wd-10p" colspan="2">Pajak (11%)</td>
                                <td class="tx-right wd-10p" id="total_tax"></td>
                            </tr>
                            <tr>
                                <td class="tx-right wd-10p" colspan="2">Diskon</td>
                                <td class="tx-right wd-10p" id="total_discount"></td>
                            </tr>
                            <tr>
                                <td class="tx-right wd-10p" colspan="2">Down Payment</td>
                                <td class="tx-right wd-10p" id="total_dp"></td>
                            </tr>

                            <tr>
                                <td class="tx-right wd-10p" colspan="2">Sisa Pembayaran</td>
                                <td class="tx-right wd-10p" id="total_remaining"></td>
                            </tr>

                            <tr>
                                <td class="tx-right wd-10p tx-uppercase tx-bold tx-inverse" colspan="2">Total</td>
                                <td class="tx-right wd-10p">
                                    <h4 class="tx-primary tx-bold" id="total_all"></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div><!-- COL-END -->
