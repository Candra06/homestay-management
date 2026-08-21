@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweet-alert/sweetalert.css"> --}}
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
    <style>
        .alert-error {
            z-index: 2000;
        }
    </style>
@endsection

@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>
            </div>

        </div>
        <div class="d-flex my-xl-auto right-content">

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
            <form method="GET" class="mb-3">
                <input type="hidden" name="filter" value="true">
                <div class="row">
                    <div class="col-md-6 col-lg-2">
                        <label for="reminder">Tanggal</label>
                        <input type="date" id="start_date" class="form-control" name="start_date"
                            value="{{ request()->start_date ?? '' }}">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="reminder"> </label>
                        <input type="date" class="form-control mt-2" id="end_date" name="end_date"
                            value="{{ request()->end_date ?? '' }}">
                    </div>

                    <div class="col-md-12 col-lg-6">
                        <div class="row">
                            <div class="col mt-2">
                                <label for=""></label>
                                <button type="submit" class="btn btn-block btn-primary ">Filter</button>
                            </div>
                            <div class="col mt-2">
                                <label for=""></label>
                                <button type="button" class="btn btn-block btn-success btn-print-resi ">Cetak Resi</button>

                            </div>
                            <div class="col btn-center-filter mt-2">
                                <label for=""></label>
                                {{-- <button type="reset" class="btn btn-block btn-danger">Reset</button> --}}
                                <a href="{{ $data->routeData }}" class="btn btn-block btn-danger" id="resetFilter">Reset</a>
                            </div>
                            <div class="col ms-1 mt-2">
                                <label for=""></label>
                                <button type="button" id="create-order" class="btn btn-block btn-info">Buat
                                    Pengiriman</button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-2 mt-2">
                        <label for=""></label>
                        <button type="button" id="setup-printer" class="btn btn-block btn-primary ">Set Printer</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table id="table" class="table  mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            @foreach ($data->tableHead as $head)
                                <th>{{ $head }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        {{-- modal delete --}}
        @php
            $product = $data->product;
        @endphp
        <x-modal-delete />
        <x-modal-create-order :product="$product" />
        <x-modal-set-printer />
    </div>
@endsection
@section('script')
    <script src="https://unpkg.com/recta/dist/recta.js"></script>
    <script>
        $(document).ready(function() {

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
            $("#create-order").click(function(event) {
                event.preventDefault();


                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();

                if (startDate == '' || endDate == '') {
                    swal({
                        text: "Tanggal mulai dan tanggal akhir harus diisi",
                        type: "error"
                    });
                } else {
                    $.ajax({
                        url: "{{ url('api/getListPacking') }}",
                        type: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate
                        }, // Send the date as a query parameter
                        timeout: 300000,
                        success: function(response) {
                            let rows = '';
                            response.data.forEach(element => {
                                rows += `
                                <tr>
                                    <td>${element.customer_name}</td>
                                    <td>${element.product.length}</td>
                                    <td>${formatRupiah(element.total_payment)}</td>
                                    <td> ${element.can_delivered == 1 || element.can_delivered =='1' ?`<input type="checkbox" id="product_id" name="product_id[]"  value="${element.id}">`:''}</td>
                                </tr>
                                `;
                            });
                            $('#modal-create-order tbody').html(rows);
                            $('#modal-create-order').modal('show');
                        },
                        error: function(xhr, status, error) {
                            swal({
                                text: "Gagal menampilkan data",
                                type: "error"
                            });

                        }
                    });
                }
            });
            $("#setup-printer").click(function (event) {
                localStorage.removeItem("selectedPrinter");
                console.log(localStorage.getItem('selectedPrinter'));

                $('#modal-set-printer').modal('show');
            })
            $("#set-printer-key").click(function (event) {
                if ($('#printer-key').val() == '') {
                    swal({
                        text: "Printer Key ID harus diisi",
                        type: "error"
                    });
                }else{

                    localStorage.setItem('selectedPrinter', $('#printer-key').val());
                     $('#modal-set-printer').modal('hide');
                }

            })
        });
        $("#table").DataTable({
            ajax: '',
            processing: true,
            serverSide: true,
            stateSave: true,
            columns: JSON.parse(`{!! json_encode($data->tableColumns) !!}`)
        });

        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }

        $('.btn-print-resi').on('click', function() {
            // Set the date parameter
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            var printerID = localStorage.getItem('selectedPrinter');

            if (startDate == '' || endDate == '') {
                swal({
                    text: "Tanggal mulai dan tanggal akhir harus diisi",
                    type: "error"
                });
            } else if (printerID == '' || printerID == null) {

                    swal({
                        text: "Printer tidak terhubung!",
                        type: "error"
                    });
                } else {
                $.ajax({
                    url: "{{ url('api/printResi') }}",
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    }, // Send the date as a query parameter
                    timeout: 300000,
                    success: function(response) {
                        // console.log(response);
                        if (response['success']) {
                            printAction(response['data']);
                            // printAction(response['data']);
                            swal({
                                text: "Berhasil mencetak resi",
                                type: "success"
                            });
                        } else {
                            swal({
                                text: response['message'],
                                type: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        swal({
                            text: "Gagal mencetak resi",
                            type: "error"
                        });

                    }
                });
            }
        });
        $('#request-shipping').on('click', function() {
            let date = $('#date').val();
            let time = $('#time').val();
            let volume = $('#volume-type').val();
            let totalProduk = "{{ count($data->product) }}";
            let selected = [];
            $('input[name="product_id[]"]:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length < 1) {
                swal({
                    target: document.getElementById('modal-create-order'),
                    text: "Tidak ada produk yang akan dikirim",
                    type: "error"
                });
            } else if (date == '') {
                swal({
                    target: document.getElementById('modal-create-order'),
                    text: "Tanggal penjemputan harus diisi",
                    type: "error"
                });
            } else if (time == '') {
                swal({
                    target: document.getElementById('modal-create-order'),
                    text: "Jam penjemputan harus diisi",
                    type: "error"
                });
            } else if (volume == '') {
                swal({
                    target: document.getElementById('modal-create-order'),
                    text: "Volume penjemputan harus diisi",
                    type: "error"
                });
            } else {

                $.ajax({
                    url: "{{ url('api/createShippingOrder') }}",
                    type: 'POST',
                    data: {
                        date: date,
                        volume: volume,
                        time: time,
                        items: selected,
                    }, // Send the date as a query parameter
                    timeout: 300000,
                    success: function(response) {


                        if (response['success']) {

                            swal({
                                target: document.getElementById('modal-create-order'),
                                text: "Berhasil menambahkan penjemputan",
                                type: "success"
                            }).then(function() {
                                location.reload();
                            });;
                        } else {
                            swal({
                                target: document.getElementById('modal-create-order'),
                                text: response['message'],
                                type: "error"
                            });
                        }
                        // location.reload();
                    },
                    error: function(xhr, status, error) {
                        swal({
                            target: document.getElementById('modal-create-order'),
                            text: "Gagal menambahkan penjemputan",
                            type: "error"
                        });

                    }
                });
            }
        });
        // function feedDot(dot) {
        //        var printerID = localStorage.getItem('selectedPrinter');
        //     var printer = new Recta(printerID, '1811')
        //     // batasi 0‑255, karena ESC J n hanya menerima 1 byte
        //     dot = Math.max(0, Math.min(dot, 255));
        //     let cmd = String.fromCharCode(0x1B, 0x4A, dot); // ESC J n
        //     return printer.raw(cmd);                           // kirim ke printer
        // }
        const DOT_PER_MM       = 8;
        const GAP_DOT = 2  * DOT_PER_MM;
        function feedDot(dot){
             var printerID = localStorage.getItem('selectedPrinter');
            var printer = new Recta(printerID, '1811')
            while(dot>0){
                const n  = Math.min(255, dot);   // ESC J n (max 255)
                const cmd= String.fromCharCode(0x1B,0x4A,n);
                printer.raw(cmd);
                dot-=n;
            }
        }

        function printAction(data) {
            try {
                var printerID = localStorage.getItem('selectedPrinter');
                console.log(printerID);

                var printer = new Recta(printerID, '1811')
                printer.open().then(function() {

                    for (let index = 0; index < data.length; index++) {
                         let totalDot = 0;
                        const order = data[index];
                        const items = order.items.map((item, i) => `${truncateText(item.name, 43)} (${item.qty})`)
                            .join("\n");


                        printer.align('center')
                        .mode('doubleWidth',true)
                        .font('A')
                        .text("------------------------------------------------")
                        // .raw("\x1B\x21\x27")
                        .text("No. Resi : "+order['resi']+"")
                        // .raw("\x1B\x21\x00")
                        .text("------------------------------------------------")
                        .bold(false)
                        .align('left')
                        .text("Pengirim : ")
                        .bold(true)
                        .text("Sukalelang")
                        .bold(false)
                        // .text('Perumahan alam hijau blok F1-34 \nBotosari,dukuh mencek\nKecamatan sukorambi \nKabupaten jember\n')
                        .text("Penerima :")
                        .bold(true)
                        .text(`${order['reciever_name']}`)
                        .bold(false)
                        .text(`${maskPhoneNumbersInText(order['receiver_address'])}`)
                        .text(`${hideString(order['receiver_phone'],2,2)}`)
                        // .text("------------------------------------------------")
                        // .text("Item : ")
                        // .text("------------------------------------------------")
                        // .text(`${items}`)
                        // .text(`${baris}`)
                        if (order['receiver_address'].length > 220) {
                            printer.text("        ")
                                    .text("        ")
                                    .text("        ");
                        }else if (order['receiver_address'].length > 192) {
                            printer.text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ");
                        }else if (order['receiver_address'].length > 144) {
                            printer.text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ");
                        }else if(order['receiver_address'].length > 96){
                            printer.text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ");
                        }else{
                            printer.text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ")
                                    .text("        ");
                        }


                        printer.text("       ")

                        // while (sisaDot > 0) {
                        //     let feedNow = Math.min(sisaDot, 255); // ESC J maksimum 255
                        //     feedDot(feedNow);
                        //     // sisaDot -= 1;
                        //     sisaDot -= feedNow;
                        // }
                        feedDot(GAP_DOT);
                        printer.cut().print();
                    }


                });

            } catch (error) {
                alert(error);
            }
        }
        function truncateText(text, maxLength) {
            return text.length > maxLength
                ? text.substring(0, maxLength - 3) + "..."
                : text;
        }
        function hideString(string, start = 2, end = 2, maskChar = '*') {
            const length = string.length;
            if (length <= (start + end)) return string;
            const maskedLength = length - start - end;
            return (
                string.slice(0, start) +
                maskChar.repeat(maskedLength) +
                string.slice(-end)
            );
        }

        function maskPhoneNumbersInText(text) {
            return text.replace(/\b(08\d{8,10})\b/g, (match, phone) => {
                const length = phone.length;
                if (length <= 4) return phone;
                return (
                    phone.slice(0, 2) +
                    '*'.repeat(length - 4) +
                    phone.slice(-2)
                );
            });
        }


        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsrsasign@10.5.25/lib/jsrsasign-all-min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/js-sha256@0.9.0/build/sha256.min.js"></script>
    <script>
        var Sha256 = {
            hash: sha256
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.0/qz-tray.min.js"></script>

    <script src="{{ asset('assets/js/qz-tray.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rsvp/4.8.5/rsvp.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.0/qz-tray.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/sha256.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rsvp/4.8.5/rsvp.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.0/qz-tray.js"></script>
    <script type="text/javascript"></script>

@endsection
