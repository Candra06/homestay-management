@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweet-alert/sweetalert.css">
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
@endsection

@section('main')
    @php
        $detail = $data->data;
        $product = $data->data->product;
    @endphp
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>
            </div>

        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pe-1 mb-xl-0">

            </div>
        </div>
    </div>

    {{-- alert component untuk handle error dan success --}}
    <x-alert />



    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $data->title }}</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-1">
                <div class="col-md-4 "><label class="d-block" for="">Batas
                        Pembayaran</label><strong>{{ App\Helper\Helpers::tanggalTime($detail->expired_time) }}</strong>
                </div>
                <div class="col-md-4 "><label class="d-block"
                        for="">Status</label><strong>{{ $detail->status }}</strong></div>
                <div class="col-md-4 "><label class="d-block" for="">Kode Pembayaran</label><a
                        href="{{ url($detail->confirmation_url) }}"
                        target="_blank"><strong>{{ $detail->confirmation_code }}</strong></a></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 "><label class="d-block" for="">Nomor
                        Resi</label><strong>{{ $detail->no_resi ?? '-' }}</strong></div>
                <div class="col-md-4 "><label class="d-block"
                        for="">Catatan</label><strong>{{ $detail->notes ?? '-' }}</strong></div>
                <div class="col-md-4 "><label class="d-block" for="">Bukti
                        Pembayaran</label>
                    @if ($detail->payment_receipt != null)
                        <strong><a data-bs-effect="effect-scale" data-bs-toggle="modal"
                                onclick="previewImage('{{ url('storage/'.$detail->payment_receipt) }}', 'Bukti Transfer')"
                                href="#modal-thumbnail">Lihat bukti transfer</a></strong>
                    @else
                        <strong>{{ $detail->payment_receipt ?? '-' }}</strong>
                    @endif
                </div>
            </div>
        </div>
        {{-- modal delete --}}
        <x-modal-delete />
        <x-modal-thumbnail />
    </div>

    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">

                </div>
                <div class="card-body">
                    <form action="">
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-xl-6">
                                {{-- <div class="card">
                                <div class="card-body h-full"> --}}
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mb-3">Ringkasan Pesanan</h4>

                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-none" id="list-produk">
                                        </div>
                                        @foreach ($product as $item)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>{{ $item->report[0]->unit_name }}</span>
                                                <strong>{{ App\Helper\Helpers::rupiah($item->report[0]->price, 'Rp. ') }}</strong>
                                            </div>
                                        @endforeach

                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Subtotal</span>
                                            <strong>{{ App\Helper\Helpers::rupiah($detail->sub_total, 'Rp. ') }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Total Ongkir</span>
                                            <strong>{{ App\Helper\Helpers::rupiah($detail->total_ongkir, 'Rp. ') }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Total</span>
                                            <strong>{{ App\Helper\Helpers::rupiah($detail->total_payment, 'Rp. ') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                {{-- </div>
                            </div> --}}
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-6 mb-4">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mb-3">Alamat Pengiriman</h4>

                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-block " id="detail-shipping">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Nama</span>
                                                <strong>{{ $detail->customer_name }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Email</span>
                                                <strong>{{ $detail->customer_email }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Telepon</span>
                                                <strong>{{ $detail->customer_phone }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Kota</span>
                                                <strong>{{ $detail->shipping_city }}</strong>
                                            </div>
                                            <div class="">
                                                <span>Alamat</span><br>
                                                <strong>{{ $detail->shipping_address }}</strong>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 mt-3 justify-content-end">
                            <div>
                                <button type="button" {{$detail->status != 'Verifying'?'disabled':''}} id="confirm-payment" class="btn btn-primary">Konfirmasi
                                    Pembayaran</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#confirm-payment').click(function() {
                swal({
                    text: "Apakah anda yakin ingin mengkonfirmasi pembayaran customer?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Konfirmasi',
                    cancelButtonText: 'Batal',
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }).then(function(isConfirm) {
                    if (isConfirm.value) {
                        $.ajax({
                            url: "{{ url('api/confirmPayment/' . $data->data->id) }}",
                            type: 'GET',
                            success: function(response) {
                                swal({
                                    text: "Berhasil mengkonfirmasi pembayaran",
                                    type: "success"
                                }).then(function() {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.log(error);

                                swal({
                                    text: "Gagal mengirim broadcast",
                                    type: "error"
                                });

                            }
                        });

                    }

                });
                // if (dateParam == '') {
                // } else {
                //     $.ajax({
                //         url: 'https://sukalelang.id/notification/cron.php',
                //         type: 'GET',
                //         data: {
                //             date: dateParam
                //         }, // Send the date as a query parameter
                //         timeout: 300000,
                //         success: function(response) {

                //             swal({
                //                 text: "Berhasil mengirim broadcast",
                //                 type: "success"
                //             });
                //         },
                //         error: function(xhr, status, error) {
                //             swal({
                //                 text: "Gagal mengirim broadcast",
                //                 type: "error"
                //             });

                //         }
                //     });
                // }

            });
        })

        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }

        function previewImage(thumbnail, alt) {

            $("#modal-thumbnail").find("img").attr("src", thumbnail)
            $("#modal-thumbnail").find("img").attr("alt", alt)
            $("#modal-thumbnail").find("h6").text(alt)

        }
    </script>
@endsection
