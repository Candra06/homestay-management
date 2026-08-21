@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweet-alert/sweetalert.css">
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
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
                    <a href="{{ $data->routeAdd }}" class="btn btn-primary">Tambah Data</a>
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
            <div class="row">
                @foreach ($data->summary as $val)
                    @php
                        $custom_class = isset($val['custom_class']) ? $val['custom_class'] : 'col-md-2 col-12';
                    @endphp
                    <div class="{{ $custom_class }}">
                        <div class="card info-card">
                            <div class="card-body d-flex d-flex align-items-center flex-row gap-3">
                                <div class="icon"
                                    style="font-size: 24px; background-color: {{ $val['color_bg'] }}; color: white; padding: 8px {{ $val['title'] == 'Product' ? '24px' : '16px' }}; border-radius: 6px;">
                                    <i class="{{ $val['icon'] }}"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold fs-6">{{ App\Helper\Helpers::rupiah($val['total'], '') }}</p>
                                    <p class="mb-0">{{ $val['title'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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

        <div class="modal fade" id="modal-detail">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title"></h6><button aria-label="Close" class="close" data-bs-dismiss="modal"
                            type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        {{-- <img src="" alt=""> --}}
                        <div class="row">

                        </div>
                        {{-- <p>Anda yakin untuk menghapus data user?
                        <div class="modal-footer border-top-0">

                            <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                        </div> --}}
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
                    }, 1);
                }
            });
        });

        function copyText(text, id) {
            navigator.clipboard.writeText(text).then(() => {
                $(`.icon-${id}`).removeClass('fa-clone')
                $(`.icon-${id}`).addClass('fa-check')
                setTimeout(() => {
                    $(`.icon-${id}`).removeClass('fa-check')
                    $(`.icon-${id}`).addClass('fa-clone')
                }, 1500);
            }).catch(err => {
                console.error("Failed to copy text to clipboard: ", err);
            });
        }

        $('.btn-export-excel').on('click', function() {
            const url = window.location.href;
            window.location.href = `${url}`.replace('/winner', '/export-winner');
        });

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
        $('#table_wrapper').css({
            'overflow-x': 'auto !important'
        });
    </script>


    <script>
        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }

        function previewImage(thumbnail, alt) {
            console.log("here");

            $("#modal-thumbnail").find("img").attr("src", thumbnail)
            $("#modal-thumbnail").find("img").attr("alt", alt)
            $("#modal-thumbnail").find("h6").text(alt)

        }

        function viewDetail(url) {

            // $('#modal-detail').html('<p>Loading...</p>');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var resp = response['data'];
                    console.log(resp);

                    let htmlData = '';
                    for (const key in resp) {
                        htmlData += '<div class="col-md-6"><label for="">' + resp[key]['title'] +
                            '</label><h6>' + resp[key]['value'] + '</h6></div>';
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
