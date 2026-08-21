@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweet-alert/sweetalert.css">
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
    <style>
        .card-body {
            padding: 1rem !important;
        }
    </style>
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
            <div class="pe-1 mb-xl-0">
                @if ($data->createBtn)
                    <a href="{{ $data->routeAdd }}"><button class="btn btn-primary">
                            Tambah Pemenang</button></a>
                @endif
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

            @if (auth()->user()->roles->code == 'OWNER' || auth()->user()->roles->code == 'FINANCE')
                <div class="row">
                    <div class="col">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #ffc107; color: white; padding: 8px 18px; border-radius: 6px;">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['omset'], 'Rp. ') }}</p>
                                    <p class="mb-0">Omzet</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #efd80a; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="far fa-handshake"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['setoran'], 'Rp. ') }}</p>
                                    <p class="mb-0">Setoran</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #e91e63; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['ongkir'], 'Rp. ') }}</p>
                                    <p class="mb-0">Ongkir</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #4caf50; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="fa fa-user-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['extrafee'], 'Rp. ') }}</p>
                                    <p class="mb-0">Extra Fee</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #4caf50; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="fa fa-user-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['fee'], 'Rp. ') }}</p>
                                    <p class="mb-0">Fee</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-3 col-12">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 30px; background-color: #ffc107; color: white; padding: 8px 16px; border-radius: 6px;">
                                    <i class="fa fa-hourglass-half"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-6">{{ $data->count->waiting }}</p>
                                    <p class="mb-0">Total Waiting</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 col-lg-3 col-12">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 30px; background-color: #efd80a; color: white; padding: 8px 16px; border-radius: 6px;">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-6">{{ $data->count->nobid }}</p>
                                    <p class="mb-0">Total No Bid</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 col-lg-3 col-12">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 30px; background-color: #e91e63; color: white; padding: 8px 16px; border-radius: 6px;">
                                    <i class="fa fa-ban"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-6">{{ $data->count->bnr }}</p>
                                    <p class="mb-0">Total Bid and Run</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 col-lg-3 col-12">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 30px; background-color: #4caf50; color: white; padding: 8px 16px; border-radius: 6px;">
                                    <i class="fa fa-user-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-6">{{ $data->count->paid }}</p>
                                    <p class="mb-0">Total Paid</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
            @if (auth()->user()->roles->code == 'SUPPLIER')
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #efd80a; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="far fa-handshake"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['setoran'], 'Rp. ') }}</p>
                                    <p class="mb-0">Setoran</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #4caf50; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="fa fa-user-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['extrafee'], 'Rp. ') }}</p>
                                    <p class="mb-0">Extra Fee</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 16px; background-color: #4caf50; color: white; padding: 8px 10px; border-radius: 6px;">
                                    <i class="fa fa-user-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-7">
                                        {{ App\Helper\Helpers::rupiah($data->summary['fee'], 'Rp. ') }}</p>
                                    <p class="mb-0">Fee</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
            <form action="" method="get" class="mb-3">
                <input type="hidden" name="filter" value="true">
                <div class="row">
                    <div class="col-md-12 col-lg-2">
                        <label for="platform">Platform</label>
                        <select name="platform" id="platform" class="form-control">
                            <option value="">Pilih Platform</option>
                            <option value="Instagram"
                                {{ request()->platform != '' && request()->platform == 'Instagram' ? 'selected' : '' }}>
                                Instagram</option>
                            <option value="Website"
                                {{ request()->platform != '' && request()->platform == 'Website' ? 'selected' : '' }}>
                                Website
                            </option>
                        </select>
                        <input type="hidden" id="code_bs" name="code_bs">
                        <input type="hidden" id="id_code_bs" name="id_bs">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="reminder">Tanggal</label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{ request()->start_date ?? '' }}">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="reminder"> </label>
                        <input type="date" class="form-control mt-2" name="end"
                            value="{{ request()->end ?? '' }}">
                    </div>
                    @if (auth()->user()->roles->code != 'SUPPLIER')
                        <div class="col-md-12 col-lg-3 col-12">
                            <label for="supplier">Supplier</label>
                            <select name="supplier" id="supplier" class="form-control">
                                <option value="">Pilih Supplier</option>
                                @foreach ($data->supplier as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request()->supplier != '' && request()->supplier == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach

                            </select>

                        </div>
                    @endif
                    <div class="col-md-12 col-lg-3">
                        <div class="row">
                            <div class="col mt-2">
                                <label for=""></label>
                                <button type="submit" class="btn btn-block btn-primary ">Cari</button>
                            </div>
                            <div class="col btn-center-filter mt-2">
                                <label for=""></label>
                                <a href="{{ $data->routeData }}" class="btn btn-block btn-danger"
                                    id="resetFilter">Reset</a>
                            </div>
                            <div class="col mt-2">
                                <label for=""></label>
                                <button type="button" class="btn btn-success btn-block btn-export-excel ">Export</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12 col-lg-6 col-12 mt-1">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mt-1">
                                <button type="submit" class="btn btn-block btn-primary ">Filter</button>
                            </div>
                            <div class="col-md-3 col-sm-6 btn-center-filter mt-1">
                                <a href="{{ $data->routeData }}" class="btn btn-block btn-danger" id="resetFilter">Reset Filter</a>
                            </div>
                            <div class="col-md-5 col-lg-4 col-sm-6 mt-1">
                                <button type="button" class="btn btn-success btn-block btn-export-excel "><i class="fa fa-file-excel"></i>&nbsp;&nbsp;Export Excel</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
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
        <x-modal-delete />
        <x-modal-thumbnail />
        <!-- Modal -->


        <div class="modal fade" id="modal-detail">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title"></h6><button aria-label="Close" class="close" data-bs-dismiss="modal"
                            type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <img src="" alt="" style="width: 100%; height: 300px; object-fit:cover;">
                        <div class="row mt-3">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $("#table").DataTable({
                ajax: '',
                processing: true,
                serverSide: true,
                stateSave: true,
                scrollX: false,
                columns: JSON.parse(`{!! json_encode($data->tableColumns) !!}`),
                initComplete: function() {
                    $('#table_wrapper').css({
                        'overflow-x': 'auto !important'
                    });
                    $('#table tbody tr').each(function() {
                        $(this).find('td:eq(1)').removeClass('d-flex');
                    });
                },
                drawCallback: function() {
                    // Remove the 'd-flex' class from the 2nd column after every redraw (search, pagination)
                    setTimeout(function() {
                        $('#table tbody tr').each(function() {
                            $(this).find('td:eq(1)').removeClass(
                                'd-flex'); // Change the index if needed
                        });
                        $("#edit-btn").each(function() {
                            let queryString = window.location.search.substring(1);
                            // console.log(queryString);

                            let originalUrl = $(this).attr("href");
                            let newUrl = originalUrl;
                            if (queryString != '') {
                                newUrl = newUrl + "?" + queryString;
                            };
                            $(this).attr("href", newUrl);

                        });
                    }, 1);

                    function getUrlParameter(param) {
                        var pageUrl = window.location.search.substring(1); // Get query string
                        var urlVariables = pageUrl.split('&'); // Split into individual params

                        for (var i = 0; i < urlVariables.length; i++) {
                            var parameterName = urlVariables[i].split('=');
                            if (parameterName[0] === param) {
                                return decodeURIComponent(parameterName[1]);
                            }
                        }
                        return null;
                    }

                    // Get the parameter value
                    var code = getUrlParameter('code_bs');
                    var idBS = getUrlParameter('id_bs');

                    if (code) {
                        // $('#table_filter.dataTables_filter input').val(code);
                        $('#code_bs').val(code);
                        $('#id_code_bs').val(idBS);
                    }
                }
            });


        });


        $('.btn-export-excel').on('click', function() {
            const url = window.location.href;
            window.location.href = `${url}`.replace('/winner', '/export-winner');
        });

        // $('#resetFilter').on('click', function() {
        //     table.search('').draw(); // Clear the search box and redraw the table
        // });

        $('[name=start_date]').on('change', function() {
            if ($(this).val() !== '') {
                $('[name=end]').prop('required', true);
                $('[name=end]').prop('min', $(this).val());

            } else {
                $('[name=end]').prop('required', false);
            }
        });



        $('[name=end]').on('change', function() {
            if ($(this).val() !== '') {
                $('[name=start_date]').prop('required', true);
                $('[name=start_date]').prop('max', $(this).val());

            } else {
                $('[name=start_date]').prop('required', false);
            }
        });
    </script>


    <script>
        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }

        function previewImage(thumbnail, alt) {
            $("#modal-thumbnail").find("img").attr("src", thumbnail)
            $("#modal-thumbnail").find("img").attr("alt", alt)
            $("#modal-thumbnail").find("h6").text(alt)

        }

        $(document).on('click', '.create-payment-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            const d = this.dataset;
            const payload = {
                report_id: Number(d.report_id),
                customer_id: Number(d.customer_id),
                customer_name: d.customer_name || '',
                customer_email: d.customer_email || '',
                shipping_city: d.shipping_city || '',
                shipping_address: d.shipping_address || '',
                customer_phone: d.customer_phone || '',
                weight: Number(d.weight || 0.2),
                price: Number(d.price || 0),
            };

            btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('api/generate-url') }}",
                method: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                dataType: 'json'
            }).done(function(res) {
                if (!res.success){
                     swal({
                        target: document.getElementById('modal-create-order'),
                        text: "Gagal generate pembayaran : "+res.message,
                        type: "error"
                    }).then(function() {
                        location.reload();
                    });
                }
                swal({
                    target: document.getElementById('modal-create-order'),
                    text: "Berhasil generate pembayaran",
                    type: "success"
                }).then(function() {
                    location.reload();
                });
            }).fail(function(xhr) {
                swal({
                    target: document.getElementById('modal-create-order'),
                    text: xhr.responseJSON?.message || 'Gagal membuat payment URL',
                    type: "error"
                }).then(function() {
                    location.reload();
                });
                alert(xhr.responseJSON?.message || 'Gagal membuat payment URL');
            }).always(function() {
                btn.prop('disabled', false);
            });
        });


        function viewDetail(url) {

            // $('#modal-detail').html('<p>Loading...</p>');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var resp = response['data'];

                    let htmlData = '';
                    for (const key in resp) {
                        if (resp[key]['title'] != 'Thumbnail') {

                            htmlData += '<div class="col-md-6 mt-1"><label for="" style="display:block;">' +
                                resp[key]['title'] + '</label><strong>' + resp[key]['value'] +
                                '</strong></div>';
                        } else {
                            console.log(resp[key]['value']);
                            $("#modal-detail").find("img").attr("src", resp[key]['value'])
                        }
                    }
                    $('#modal-detail').find('.row').html(htmlData);
                    $('#modal-detail').find('.modal-title').html(response['title']);
                },
                error: function() {
                    alert('There was an error loading the modal content.');
                }
            });


        }
    </script>
@endsection
