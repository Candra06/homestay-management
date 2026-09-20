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
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="card-title mg-b-0">{{ $data->title }}</h4>
                <div>
                    <a href="{{ url('booking') }}" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i>
                        Kembali</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class=" d-flex flex-row justify-content-between ps-0 ms-0">
                <div class="form-group col-md-2 ps-0">
                    <label for="">Filter Tanggal</label>
                    <input type="date" id="filter-date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

            </div>
            <div id="loading">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div>
                    <p class="ms-2 tx-center">Memuat Data...</p>
                </div>
            </div>
            <div class="row" id="room-list">

            </div>
        </div>
        {{-- modal delete --}}
        <x-modal-delete />
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var currentUrl = $(location).attr('href');
        var urlParts = currentUrl.replace(/\/$/, "").split('/');
        var getId = urlParts[urlParts.length - 1];
        var currentDate = "{{ date('Y-m-d') }}";

        $(document).ready(function() {
            getRooms(getId, currentDate);

            $('#filter-date').on('change', function() {
                currentDate = $(this).val();
                getRooms(getId, currentDate);
            });
        });

        function getRooms(roomId, date) {
            $("#loading").show();
            $("#room-list").hide();
            $.ajax({
                url: "{{ url('room/availability/') }}" + "/" + roomId,
                type: "GET",
                data: {
                    date: currentDate,
                },
                dataType: "JSON",
                success: function(response) {
                    generateCard(response.data);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function() {
                    $("#loading").hide();
                    $("#room-list").show();
                }
            });
        }

        function generateCard(data) {
            let html = '';
            data.forEach(item => {
                let bgCard = "bg-success";
                switch (item.status) {
                    case 'Tersedia':
                        bgCard = "bg-success";
                        break;
                    case 'Booked':
                        bgCard = "bg-warning";
                        break;
                    case 'Terisi':
                        bgCard = "bg-warning";
                        break;
                    case 'Check-In':
                        bgCard = "bg-danger";
                        break;
                    case 'Cleaning':
                        bgCard = "bg-primary";
                        break;
                    case 'Maintenance':
                        bgCard = "bg-secondary";
                        break;
                    default:
                        break;
                }

                html += `
                    <div class="col-md-2">
                        <div class="card ${bgCard}">
                            <div class="card-body tx-white">
                                <h3 class=" tx-center">${item.room_number}</h3>
                                <p class="tx-center mb-0">${item.status}</p>
                                <p class="tx-center ${item.status == 'Booked' ? 'mb-0' : 'mb-1 pb-3'}">${item.status == 'Booked' ? item.booking_code : ''}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#room-list').html(html);
        }
    </script>
@endsection
