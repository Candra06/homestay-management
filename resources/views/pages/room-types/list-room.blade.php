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
    <x-alert />
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
    <div class="modal fade" id="modal-update">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title"></h6><button aria-label="Close" class="close"
                        data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/room/update/') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="status">Status Kamar<span class="tx-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Pilih Status Kamar</option>
                                <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>
                                    Tersedia</option>
                                <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>
                                    Maintenance</option>
                            </select>
                        </div>


                        <div class="form-group d-flex flex-row justify-content-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                        bgCard = "bg-danger";
                        break;
                    case 'Check-In':
                        bgCard = "bg-warning";
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
                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" onclick="updateRoomStatus(${item.id},${item.room_number})" href="#modal-update">
                        <div class="card ${bgCard}">
                            <div class="card-body tx-white">
                                <h3 class=" tx-center">${item.room_number}</h3>
                                <p class="tx-center mb-0">${item.status}</p>
                                <p class="tx-center ${['Booked', 'Check-In'].includes(item.status) ? 'mb-0' : 'mb-1 pb-3'}">${['Booked', 'Check-In'].includes(item.status) ? item.booking_code : ''}</p>
                            </div>
                        </div>
                    </a>
                </div>
                `;
            });
            $('#room-list').html(html);
        }

        function updateRoomStatus(roomId, roomNumber) {
            let url = "{{ url('room/update-status/') }}/" + roomId
            $("#modal-update").find("form").attr("action", url)
            $("#modal-update").find(".modal-title").text(`Update Status Kamar ${roomNumber}`)
        }
    </script>
@endsection
