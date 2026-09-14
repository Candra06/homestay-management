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
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">Dashboard / <strong
                        class="text-black">{{ $data->title }}</strong>
                </span>
            </div>

        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>

    {{-- alert component untuk handle error dan success --}}
    <x-alert />



    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="card-title mg-b-0">{{ $data->title }}</h4>
                <div>
                    <button class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Kembali</button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class=" d-flex flex-row justify-content-between ps-0 ms-0">
                <div class="form-group col-md-2 ps-0">
                    <label for="">Filter Tanggal {{bcrypt('admin-ezzy')}}</label>
                    <input type="date" id="filter-date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                
            </div>
            <div class="row">

                <div class="col-md-2">
                    <div class="card bg-success">
                        <div class="card-body">
                            <h3 class=" tx-center">101</h3>
                            <p class="tx-center">Available</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h3 class=" tx-center">102</h3>
                            <p class="tx-center">Booked</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <h3 class=" tx-center tx-white">103</h3>
                            <p class="tx-center tx-white">Cleaning</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{-- modal delete --}}
        <x-modal-delete />
    </div>
@endsection
@section('script')
    <script></script>


    <script></script>
@endsection
