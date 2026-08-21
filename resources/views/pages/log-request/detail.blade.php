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
        // $product = $data->data->product;
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
                <div class="col-md-4 ">
                    <label class="d-block" for="">Tanggal Request</label>
                    <strong>{{ App\Helper\Helpers::tanggalTime($detail->created_at) }}</strong>
                </div>
                <div class="col-md-4 "><label class="d-block" for="">Url</label><strong>{{ $detail->url }}</strong>
                </div>
                <div class="col-md-4 "><label class="d-block"
                        for="">Type</label><strong>{{ $detail->type }}</strong></div>
                {{-- <div class="col-md-4 "><label class="d-block" for="">Kode Pembayaran</label><a
                        href="{{ url($detail->confirmation_url) }}"
                        target="_blank"><strong>{{ $detail->confirmation_code }}</strong></a></div> --}}
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
                                    <h4 class="card-title mb-3">Request</h4>

                                </div>
                                <div class="card">
                                    <div class="card-body">

                                        <div class="d-block">
                                            <pre>
                                                <code class="language-json">
                                                    {{json_encode($detail->request,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)}}
                                                </code>
                                            </pre>
                                            {{-- {!! $detail->request !!} --}}
                                        </div>

                                    </div>
                                </div>
                                {{-- </div>
                            </div> --}}
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-6 mb-4">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mb-3">Response</h4>

                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-block " id="detail-shipping">
                                             <pre>
                                                <code class="language-json">
                                                    {{json_encode($detail->response,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)}}
                                                </code>
                                            </pre>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group mb-0 mt-3 justify-content-end">
                            <div>
                                <button type="button" {{$detail->status != 'Verifying'?'disabled':''}} id="confirm-payment" class="btn btn-primary">Konfirmasi
                                    Pembayaran</button>
                            </div>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

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
