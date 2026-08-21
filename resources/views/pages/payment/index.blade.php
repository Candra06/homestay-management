@extends('template.app-frontend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweet-alert/sweetalert.css">
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
@endsection
@section('main')
    <div class="container mg-t-90">
        <x-alert />
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between ">
            <div class="left-content">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Konfirmasi Pembayaran</h2>
                    <p class="mg-b-0 text-danger">Lengkapi alamat pengiriman dan unggah bukti pembayaran.</p>
                </div>
            </div>
            <div class="me-2 row" style="width: 280px;">

                <div class="col-lg-4 col-md-12 ">
                    <label class="tx-13">Sisa Waktu</label>
                    <h5 id="timer" class="tx-bold"><strong>{{ $data->expired_time }}</strong></h5>
                </div>
                <div class="col-lg-4 col-md-12">
                    <label class="tx-13">Status</label>
                    <h5><strong>{{ $data->status }}</strong></h5>
                </div>
                <div class="col-lg-4 col-md-12">
                    <label class="tx-13">Kode</label>
                    <h5><strong>{{ $data->confirmation_code }}</strong></h5>
                </div>
            </div>
        </div>

        <!-- /breadcrumb -->
        <div class="row row-sm">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header pd-b-0 pd-t-20 bd-b-0">
                        <h4 class="card-title mb-0">Informasi Bank Transfer</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="">Nama Rekening</label>
                                <h5>Mohamad Rajif Taufik Widodo</h5>
                            </div>
                            <div class="col-sm-12 col-md-4 col-md-4">
                                <label for="">Bank</label>
                                <h5>BCA</h5>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <label for="">Nomor Rekening</label>
                                <h5 class="">0140032174 <span class="fa fa-clone norek " style="color: #0000ff;"
                                        onclick="copyText('0140032174', 'norek')"></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row opened -->
        <div class="d-flex justify-content-between">
            <h4 class="card-title mb-0">Konfirmasi Pembayaran</h4>
            <i class="mdi mdi-dots-horizontal text-gray"></i>
        </div>
        <p class="mg-b-4 mb-4 ">Lakukan pembayaran sebelum {{ App\Helper\Helpers::tanggalTime($data->expired_time) }}.</p>

        <div class="row row-sm">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">

                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 mb-3">
                                <label for="">Mohon teliti data yang telah Anda masukkan. Dimohon tidak melakukan
                                    transfer sebelum memastikan alamat pengiriman dan jumlah bayar telah sesuai. Kesalahan
                                    input data bukan tanggung jawab kami. <a href="https://sukalelang.id/kebijakan-privasi/"
                                        target="_blank">Kebijakan Privasi</a></label>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-6 mb-4">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mb-3">Alamat Pengiriman</h4>
                                    {{-- @if ($data->status == 'Waiting')
                                        <a id="show-edit-form" style="cursor: pointer;">Edit</a>
                                    @endif --}}
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        @if ($data->status == 'Waiting')
                                            <div class="d-block" id="form-shipping-edit">
                                                <form class="" id="shipping-form">
                                                    <div class="form-group">
                                                        <label for="">Nama </label>
                                                        <input type="text" class="form-control" id="customerName"
                                                            name="customerName" value="{{ $data->customer_name }}"
                                                            placeholder="Nama ">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Email </label>
                                                        <input type="email" class="form-control" id="customerEmail"
                                                            name="customerEmail" value="{{ $data->customer_email }}"
                                                            placeholder="Email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Telepon </label>
                                                        <input type="text" class="form-control" id="customerPhone"
                                                            name="customerPhone" value="{{ $data->customer_phone }}"
                                                            placeholder="Nomor Telepon Penerima">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Kota</label>
                                                        <input type="hidden" value="{{ $data->address_id }}"
                                                            id="addressId">
                                                        <select name="customerCity"
                                                            class="select2 customerCity form-control"
                                                            placeholder="pilih lokasi pengiriman"
                                                            value="{{ $data->shipping_city ?? '' }}">

                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Alamat</label>
                                                        <textarea name="customerAddress" id="customerAddress" cols="30" rows="3" class="form-control">{{ $data->shipping_address }}</textarea>

                                                    </div>

                                                    {{-- <div class="form-group mb-0 mt-3 justify-content-end">
                                                <div>
                                                    <button type="button" id="submit-shipping-info"
                                                        class="btn btn-primary">Simpan</button>
                                                    <button type="reset" id="cancel-shipping-info"
                                                        class="btn btn-secondary">Batal</button>
                                                </div>
                                            </div> --}}
                                                </form>
                                            </div>
                                        @else
                                            <div class="{{ $data->address_id == null ? 'd-none' : 'd-block' }} "
                                                id="detail-shipping">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span>Nama</span>
                                                    <strong id="customer-name">{{ $data->customer_name }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span>Email</span>
                                                    <strong
                                                        id="customer-email">{{ $data->customer_email ?? '-' }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span>Nomor Telepon</span>
                                                    <strong
                                                        id="customer-phone">{{ $data->customer_phone ?? '-' }}</strong>
                                                </div>
                                                <div class=" mb-3">
                                                    <span>Kota</span><br>
                                                    <strong
                                                        id="customer-shipping_city">{{ $data->shipping_city ?? '-' }}</strong>
                                                </div>
                                                <div class="">
                                                    <span>Alamat</span><br>
                                                    <strong
                                                        id="customer-shipping_address">{{ $data->shipping_address }}</strong>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-6">
                                {{-- <div class="card">
                                    <div class="card-body h-full"> --}}
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mb-3">Ringkasan Pesanan</h4>
                                    {{-- <a id="show-detail" style="cursor: pointer;">Detail</a> --}}
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-block" id="list-produk">
                                            @foreach ($data->product as $item)
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span> {{ $item->report[0]->unit_name }}</span>
                                                    <strong>{{ App\Helper\Helpers::rupiah($item->report[0]->price, 'Rp. ') }}</strong>
                                                </div>
                                            @endforeach

                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Subtotal</span>
                                            <strong>{{ App\Helper\Helpers::rupiah($data->sub_total, 'Rp. ') }}</strong>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span>Total Ongkir</span>
                                                <div class="text-end">
                                                    <strong
                                                        id="payment-ongkir">{{ App\Helper\Helpers::rupiah($data->total_ongkir, 'Rp. ') }}</strong>
                                                </div>
                                            </div>
                                            {{-- @if ($data->total_ongkir == 0 && $data->address_id == null) --}}
                                            <div class="w-full text-center" id="alertOngkir">
                                                <span class="badge bg-danger w-full" style="width: 100%;">Silahkan isi
                                                    kolom kota dan alamat terlebih dahulu</span>
                                                {{-- <small class="text-danger">Harap melengkapi alamat pengiriman untuk
                                                    kalkulasi ongkir</small> --}}
                                            </div>
                                            {{-- @endif --}}
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Total</span>
                                            <strong
                                                id="payment-total">{{ App\Helper\Helpers::rupiah($data->total_payment, 'Rp. ') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                {{-- </div>
                                </div> --}}
                            </div>

                        </div>
                        @if ($data->status == 'Verifying' || $data->status == 'Done' || $data->status == 'Packing')
                            <div class="row">
                                <div class="col-md-12 col-lg-4 col-sm-12">
                                    <label class="d-block" for="">Catatan</label>
                                    <strong>{{ $data->notes ?? '-' }}</strong>
                                </div>
                                <div class="col-md-12 col-lg-4 col-sm-12">
                                    <label class="d-block" for="">Nomor Resi</label>
                                    <strong>{{ $data->no_resi ?? '-' }} <span class="fa fa-clone noresi "
                                            style="color: #0000ff;"
                                            onclick="copyText('{{ $data->no_resi ?? '-' }}', 'noresi')"></span></strong>
                                </div>
                                <div class="col-md-12 col-lg-4 col-sm-12">
                                    <label class="d-block" for="">Bukti Pembayaran</label>
                                    @if ($data->payment_receipt != null)
                                        <strong><a data-bs-effect="effect-scale" data-bs-toggle="modal"
                                                onclick="previewImage('{{ url('storage/' . $data->payment_receipt) }}', 'Bukti Transfer')"
                                                href="#modal-thumbnail">Lihat bukti transfer</a></strong>
                                    @else
                                        <strong>{{ $data->payment_receipt ?? '-' }}</strong>
                                    @endif
                                </div>

                            </div>
                        @else
                            {{-- {{ dd($errors->all()) }} --}}
                            <form action="{{ url('submit_payment/' . $data->id) }}" id="submitPayment" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Catatan</label>
                                    <textarea id="" cols="30" rows="3" class="form-control" placeholder="Opsional" name="notes"></textarea>

                                </div>
                                <div class="form-group">
                                    <label for="">Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="payment_receipt" required
                                        name="payment_receipt" placeholder="Bukti Pembayaran">
                                    <small id="error-payment_receipt" class="text-danger"></small>

                                </div>
                                <div class="form-group mb-0 justify-content-end">
                                    <div class="checkbox">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" required name="accept"
                                                class="custom-control-input" id="checkbox-2">
                                            <label for="checkbox-2" class="custom-control-label mt-1">Saya sudah
                                                membaca dan setuju dengan situs <a
                                                    href="https://sukalelang.id/kebijakan-privasi/" target="_blank">syarat
                                                    dan
                                                    ketentuan</a></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-primary">BUAT PESANAN</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        <!-- row closed -->
        <x-modal-thumbnail />
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getDefaultAddress();
            let totalOngkir = `{{ $data->total_ongkir }}`;
            let addressDataId = $("#addressId");
            let customerName = $("#customerName");
            let customerEmail = $("#customerEmail");
            let customerPhone = $("#customerPhone");
            let customerCity = $("#customerCity");
            let customerAddress = $("#customerAddress");
            let alert = $("#alertOngkir");
            if (addressDataId.val() == '') {
                alert.show();

            } else {
                alert.hide();
            }
            customerName.change(function() {
                updateShippingAddress();
            });
            customerEmail.change(function() {
                updateShippingAddress();
            });
            customerPhone.change(function() {
                updateShippingAddress();
            });
            customerCity.on('select2:select', function() {
                updateShippingAddress();
            });
            customerAddress.change(function() {
                updateShippingAddress();
            });
            $("#show-detail").click(function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                let detail = $("#list-produk");

                if (detail.hasClass('d-none')) {

                    detail.removeClass("d-none");
                    detail.addClass("d-block");
                } else {

                    detail.removeClass("d-block");
                    detail.addClass("d-none");
                }
            });
            $("#show-edit-form").click(function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                let formEdit = $("#form-shipping-edit");
                let detail = $("#detail-shipping");
                $('#error-payment_receipt').text('');

                if (formEdit.hasClass('d-none')) {

                    formEdit.removeClass("d-none");
                    formEdit.addClass("d-block");
                } else {
                    formEdit.removeClass("d-block");
                    formEdit.addClass("d-none");
                }
                if (detail.hasClass('d-none')) {
                    detail.removeClass("d-none");
                    detail.addClass("d-block");
                } else {

                    detail.removeClass("d-block");
                    detail.addClass("d-none");
                }
            });
            $('#submit-shipping-info').on('click', function() {

            })
            $('#submitPayment').submit(function(e) {
                e.preventDefault();
                var ongkir = `{{ $data->total_ongkir }}`;
                if (addressDataId.val() == '') {
                    swal({
                        text: "Alamat pengiriman harus dilengkapi",
                        type: "warning"
                    });
                } else {
                    let form = $(this);
                    let formData = new FormData(this);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            if (xhr.status ===
                                422) { // Unprocessable Entity = error validasi Laravel
                                let errors = xhr.responseJSON.errors;
                                // Tampilkan error untuk tiap field yang error
                                if (errors.payment_receipt) {
                                    $('#error-payment_receipt').text(errors.payment_receipt[0]);
                                }
                                // Jika ada error field lain, tambahkan juga seperti ini
                                // if(errors.notes) {
                                //     $('#error-notes').text(errors.notes[0]);
                                // }
                            } else {
                                // Untuk error lain (server error, dll)
                                alert('Terjadi kesalahan, silakan coba lagi.');
                            }
                        }
                    });

                }
            });
            $('.select2').each(function() {
                var select = $(this);
                var shippingData = "{{ $data->shipping_city }}" ?? '';
                var placeholder = shippingData.split(',');
                // var placeholder = $(this).attr('placeholder').split(/\s+/);


                select.select2({
                    placeholder: placeholder.length <= 1 ? 'Pilih Provinsi/Kota/Kecamatan' :
                        placeholder[0] + ' ' + placeholder[1],
                    searchInputPlaceholder: 'Cari nama provinsi/kota/kecamatan',
                    dropdownPosition: 'below',
                    minimumInputLength: 4,
                    ajax: {
                        url: "{{ url('api/searchAddress') . '?keyword=' }}",
                        dataType: 'json',
                        delay: 20,
                        data: function(params) {
                            return {
                                keyword: params.term
                            };
                        },
                        processResults: function(dt) {
                            return {
                                results: dt.data.map(function(item) {
                                    return {
                                        id: item._id,
                                        text: item.CITY_NAME + ", " + item
                                            .SUBDISTRICT_NAME + ", " + item
                                            .DISTRICT_NAME + "(" + item.CITY_NAME_SI +
                                            ")"
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                });
                select.on('change', function() {
                    let selectedData = select.select2('data')[0];

                    updateShippingAddress();
                });
            });

        });

        function updateShippingAddress() {
            const body = {
                customerCity: $(".customerCity option:selected").text(),
                addressID: $(".customerCity").val(),
                customerName: $("#customerName").val(),
                customerEmail: $("#customerEmail").val(),
                customerAddress: $("#customerAddress").val(),
                customerPhone: $("#customerPhone").val(),
            }
            $.ajax({
                url: "{{ url('api/editShipping/' . $data->id) }}",
                type: "POST",
                data: body,
                success: function(response) {
                    if (!response.success) {
                        swal({
                            text: response.message,
                            type: "warning"
                        });
                    } else {
                        let detailData = response.data;
                        // $("#customerCity").val(detailData.shipping_city);
                        $("#customerName").val(detailData.customer_name);
                        $("#customerEmail").val(detailData.customer_email);
                        $("#customerAddress").val(detailData.shipping_address);
                        $("#customerPhone").val(detailData.customer_phone);
                        // // seet to text
                        $("#customer-shipping_city").text(detailData.shipping_city);
                        $("#customer-name").text(detailData.customer_name);
                        $("#customer-email").text(detailData.customer_email);
                        $("#customer-shipping_address").text(detailData.shipping_address);
                        $("#customer-phone").text(detailData.customer_phone);
                        $("#payment-ongkir").text(formatRupiah(detailData.total_ongkir));
                        $("#payment-total").text(formatRupiah(detailData.total_payment));
                        $("#addressId").val(detailData.address_id);
                        totalOngkir = detailData.total_ongkir;
                        // let formEdit = $("#form-shipping-edit");

                        // if (formEdit.hasClass('d-none')) {

                        //     formEdit.removeClass("d-none");
                        //     formEdit.addClass("d-block");
                        // } else {
                        //     formEdit.removeClass("d-block");
                        //     formEdit.addClass("d-none");
                        // }
                        if ($("#addressId").val() == '') {
                            $("#alertOngkir").show();

                        } else {
                            $("#alertOngkir").hide();

                        }
                        getDefaultAddress();
                    }
                },
                error: function(error) {


                }
            })
        }

        function copyText(text, id) {
            navigator.clipboard.writeText(text).then(() => {
                $(`.` + id).removeClass('fa-clone')
                $(`.` + id).addClass('fa-check')
                setTimeout(() => {
                    $(`.` + id).removeClass('fa-check')
                    $(`.` + id).addClass('fa-clone')
                }, 1500);
            }).catch(err => {
                console.error("Failed to copy text to clipboard: ", err);
            });
        }

        function previewImage(thumbnail, alt) {

            $("#modal-thumbnail").find("img").attr("src", thumbnail)
            $("#modal-thumbnail").find("img").attr("alt", alt)
            $("#modal-thumbnail").find("h6").text(alt)

        }

        function getDefaultAddress() {
            var tmpAddress = "{{ $data->shipping_city }}";

            let address = tmpAddress.split(",");
            $.ajax({
                url: "{{ url('api/searchAddress') . '?keyword=' }}" + address[0] + " " + address[1],
                type: "GET",
                dataType: "json",
                success: function(response) {
                    let item = response.data[0];

                    if (item != undefined) {

                        $('.select2').select2({
                            placeholder: item.CITY_NAME + " " + item.SUBDISTRICT_NAME + " " + item
                                .DISTRICT_NAME,
                            // placeholder: item.CITY_NAME + ", " + item.SUBDISTRICT_NAME + ", " + item
                            //     .DISTRICT_NAME + "(" + item.CITY_NAME_SI + ")",
                            searchInputPlaceholder: 'Cari nama provinsi/kota/kecamatan',
                            dropdownPosition: 'below',
                            minimumInputLength: 4,
                            ajax: {
                                url: "{{ url('api/searchAddress') . '?keyword=' }}",
                                dataType: 'json',
                                delay: 20,
                                data: function(params) {
                                    return {
                                        keyword: params.term
                                    };
                                },
                                processResults: function(dt) {
                                    return {
                                        results: dt.data.map(function(item) {
                                            return {
                                                id: item._id,
                                                text: item.CITY_NAME + ", " + item
                                                    .SUBDISTRICT_NAME + ", " + item
                                                    .DISTRICT_NAME + "(" + item
                                                    .CITY_NAME_SI +
                                                    ")"
                                            };
                                        })
                                    };
                                },
                                cache: true
                            }
                        });
                    }

                },
                error: function(error) {
                    console.log(error);

                }
            })
            // }
        }
        // Set the date we're counting down to
        var countDownDate = new Date("{{ $data->expired_time }}").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("timer").innerHTML = (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds) + "";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = "EXPIRED";
            }
        }, 1000);

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }
    </script>
@endsection
