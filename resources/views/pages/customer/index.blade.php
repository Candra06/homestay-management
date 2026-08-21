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
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>
            </div>

        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pe-1 mb-xl-0">
                {{-- @if ($data->createBtn)
                    <a href="{{ $data->routeAdd }}"><button class="btn btn-primary">
                            Tambah Supplier</button></a>
                @endif --}}
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
            <form action="" method="get" class="mb-3">
                <input type="hidden" name="filter" value="true">
                <div class="row">
                    <div class="col-md-12 col-lg-2">
                        <label for="status">Status Akun</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Pilih Status akun</option>
                            <option value="0"
                                {{ request()->status != '' && request()->status == '0' ? 'selected' : '' }}>
                                Unverify</option>
                            <option value="1"
                                {{ request()->status != '' && request()->status == '1' ? 'selected' : '' }}>
                                Verified
                            </option>
                             <option value="2"
                                {{ request()->status != '' && request()->status == '2' ? 'selected' : '' }}>
                                Blocked
                            </option>
                        </select>
                    </div>

                     <div class="col-md-6 col-lg-2">
                        <label for="reminder">Tanggal Registrasi</label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{ request()->start_date ?? '' }}">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="reminder"> </label>
                        <input type="date" class="form-control mt-2" name="end_date"
                            value="{{ request()->end_date ?? '' }}">
                    </div>

                    <div class="col-md-12 col-lg-3">
                        <div class="row">
                            <div class="col mt-2">
                                <label for=""></label>
                                <button type="submit" class="btn btn-block btn-primary ">Filter</button>
                            </div>
                            <div class="col btn-center-filter mt-2">
                                <label for=""></label>
                                <a href="{{ $data->routeData }}" class="btn btn-block btn-danger" id="resetFilter">Reset</a>
                            </div>

                        </div>
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
        <x-modal-delete />
    </div>
@endsection
@section('script')
    <script>
        $("#table").DataTable({
            ajax: '',
            processing: true,
            serverSide: true,
            stateSave: true,
            columns: JSON.parse(`{!! json_encode($data->tableColumns) !!}`)

        });

        function blockUsers(id, email, whatsapp, message) {

            swal({
                text: message,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Konfirmasi',
                cancelButtonText: 'Batal',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }).then(function(isConfirm) {
                if (isConfirm.dismiss == 'cancel') {
                    swal.close();
                } else if (isConfirm.value) {

                    $.ajax({
                        url: "{{ url('api/confirmBlock/') }}" + "/" + id,
                        type: 'GET',
                        success: function(response) {
                            swal({
                                text: response['message'],
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
            })
        }
    </script>


    <script>
        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }
    </script>
@endsection
