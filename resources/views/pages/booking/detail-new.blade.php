@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('css')
    <style>
        #toast-container.toast-top-center {
            top: 15% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
        }

        /* Mengatur tingkat opacity (transparansi) kotak toast */
        #toast-container>.toast {
            opacity: 0.90 !important;
            /* Ubah angka sesuai keinginan (0.0 - 1.0) */
            filter: alpha(opacity=90) !important;
        }
    </style>
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">Dashboard
                    <span class="text-muted mt-1 tx-13 ms-2 mb-0">/ {{ $data->subtitle }} </span> /
                </span>
                <span class="content-title ms-2 tx-13 mb-0 mt-1"> {{ $data->title }}</span>

            </div>
        </div>

    </div>
    @php
        Carbon\Carbon::setLocale('id_ID');
        $data = $data->bookingData;
        $room = [];
        $additional = [];
        foreach ($data->bookingRooms as $value) {
            $checkIn = new DateTime($value->checkin_date);
            $checkOut = new DateTime($value->checkout_date);
            $nights = $checkIn->diff($checkOut)->days;
            $totalPrice = $value->room->roomType->base_price * $nights;
            $room[] = (object) [
                'jenis' => 'Kamar',
                'room_type' => $value->room->roomType->type_name,
                'room_number' => $value->room->room_number,
                'base_price' => App\Helper\Helpers::rupiah($value->room->roomType->base_price),
                'checkin_date' => App\Helper\Helpers::tanggal($value->checkin_date),
                'checkout_date' => App\Helper\Helpers::tanggal($value->checkout_date),
                'nights' => $nights . ' Malam',
                'total_price' => App\Helper\Helpers::rupiah($totalPrice),
            ];
            foreach ($value->additionals as $add) {
                $additional[] = (object) [
                    'jenis' => 'Additional',
                    'name' => $add->additional->name . '(' . $value->room->room_number . ')',
                    'price' => App\Helper\Helpers::rupiah($add->additional->price),
                    'total_price' => App\Helper\Helpers::rupiah($add->total_price),
                    'checkin_date' => App\Helper\Helpers::tanggal($value->checkin_date),
                    'checkout_date' => App\Helper\Helpers::tanggal($value->checkout_date),
                    'nights' => $nights . ' Malam',
                ];
            }
        }
        foreach ($data->additional as $add) {
            $additional[] = (object) [
                'jenis' => 'Service Charge',
                'name' => $add->item->name,
                'price' => App\Helper\Helpers::rupiah($add->item->price),
                'total_price' => App\Helper\Helpers::rupiah($add->item->price),
                'checkin_date' => '-',
                'checkout_date' => '-',
                'nights' => '-',
            ];
        }
    @endphp
    <div class="d-flex flex-row justify-content-between mb-3">
        <div class="">
            <h3>#{{ $data->booking_code }} {!! App\Helper\Helpers::generateStatus($data->booking_status) !!}</h3>
            <span class="text-secondary"><i class="fa fa-clock"></i> Dibuat pada
                {{ App\Helper\Helpers::tanggalTime($data->created_at) }}</span> •
            <span class="text-secondary"><i class="fa fa-user"></i> {{ $data->userCreate->name }}</span>
        </div>
        <div>
            <a class="btn btn-outline-primary me-1" href="{{ url('/booking') }}"><i class="fa fa-arrow-left"></i>
                Kembali</a>
            @if ($data->grand_total != $data->amount_paid)
                <a class="btn btn-primary me-1" ata-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-payment"><i
                        class="fa fa-money-bill"></i> Buat
                    Pelunasan</a>
            @elseif ($data->grand_total == $data->amount_paid && $data->booking_status == 'Approved')
                <button type="button" id="btn-checkin-process" class="btn btn-info me-1"><i class="fa fa-check"></i> Check
                    In</button>
            @elseif ($data->booking_status == 'Checked-In')
                <button type="button" id="btn-checkout-process" class="btn btn-warning me-1"><i class="fe fe-log-out"></i>
                    Check Out</button>
            @endif


            <button class="btn btn-success print-invoice" onclick=""><i class="fa fa-print"></i>
                {{ $data->grand_total != $data->amount_paid ? 'Cetak Bukti Pembayaran' : 'Cetak Invoice' }}</button>

        </div>
    </div>


    <x-alert />

    <div class="row">
        <div class="col-md-8 col-lg-8">
            <div class="card box-shadow">
                <div class="card-header">
                    <h5>Items Pemesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Checkin Date</th>
                                    <th>Checkout Date</th>
                                    <th>Lama</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($room as $value)
                                    <tr>
                                        <td>{{ $value->jenis }}</td>
                                        <td>{{ $value->room_type }} - {{ $value->room_number }}</td>
                                        <td>{{ $value->base_price }}</td>
                                        <td>{{ $value->checkin_date }}</td>
                                        <td>{{ $value->checkout_date }}</td>
                                        <td>{{ $value->nights }}</td>
                                        <td>{{ $value->total_price }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($additional as $value)
                                    <tr>
                                        <td>{{ $value->jenis }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->price }}</td>
                                        <td>{{ $value->checkin_date }}</td>
                                        <td>{{ $value->checkout_date }}</td>
                                        <td>{{ $value->nights }}</td>
                                        <td>{{ $value->total_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card box-shadow">
                <div class="card-header">
                    <h5>Informasi Tamu</h5>
                </div>
                <div class="card-body">
                    <div class="row mt-0">
                        <div class="col-md-6">
                            <span class="text-secondary">Nama Tamu</span>
                            <p><strong>{{ $data->guest->nama_lengkap }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary">Email</span>
                            <p><strong>{{ $data->guest->email }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary">No Telp</span>
                            <p><strong>{{ $data->guest->no_telp }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary">Identity Type</span>
                            <p><strong>{{ strtoupper($data->guest->identity_type) }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary">Identity Number</span>
                            <p><strong>{{ $data->guest->identity_number }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary">Address</span>
                            <p><strong>{{ $data->guest->address }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary">Foto Identitas</span>
                            @if ($data->guest->attachment)
                                <img src="{{ url('storage/' . $data->guest->attachment->file_url) }}"
                                    alt="{{ $data->guest->nama_lengkap }}" class="img-fluid">
                            @else
                                <p>Tidak ada foto identitas</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="card box-shadow">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h5>Pembayaran</h5>
                    <h5 class="tx-secondary">#{{ $data->invoices[0]->invoice_number }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Status Pembayaran</span>
                        <span class="tx-semibold">{!! App\Helper\Helpers::generateStatusPayment($data->payment_status) !!}</span>
                    </div>
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Metode Pembayaran</span>
                        <span class="tx-semibold">{{ $data->payment_method ?? '-' }}</span>
                    </div>
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Tanggal Pembayaran</span>
                        <span class="tx-semibold">{{ App\Helper\Helpers::tanggalTime($data->paid_at) }}</span>
                    </div>
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Subtotal</span>
                        <span class="tx-semibold">{{ App\Helper\Helpers::rupiah($data->subtotal) }}</span>
                    </div>
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Pajak(11%)</span>
                        <span class="tx-semibold">{{ App\Helper\Helpers::rupiah($data->tax) }}</span>
                    </div>
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Diskon</span>
                        <span class="tx-semibold">{{ App\Helper\Helpers::rupiah($data->discount_amount * -1) }}</span>
                    </div>
                    
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span>Down Payment</span>
                        <span class="tx-semibold">{{ App\Helper\Helpers::rupiah($data->down_payment) }}</span>
                    </div>
                    @php
                        $sisa = 0;
                        if ($data->grand_total != $data->amount_paid) {
                            $sisa = $data->grand_total - $data->amount_paid;
                        } else {
                            $sisa = $data->amount_paid;
                        }

                    @endphp
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span> {{ $data->grand_total != $data->amount_paid ? 'Sisa Pembayaran' : 'Terbayar' }}</span>
                        <span class="tx-semibold">{{ App\Helper\Helpers::rupiah($sisa)  }}</span>
                    </div>
                    <hr>
                    <div class="d-flex mb-2 flex-row justify-content-between">
                        <span class="tx-semibold">Total</span>
                        <h5 class="tx-primary tx-bold">{{ App\Helper\Helpers::rupiah($data->grand_total) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="modal fade" id="modal-payment">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Tambah Pelunasan</h6><button aria-label="Close" class="close"
                        data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/booking/payment/' . $data->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Sisa Pembayaran</label>
                            <input type="text" readonly
                                value="{{ App\Helper\Helpers::rupiah($data->grand_total - $data->amount_paid) }}"
                                class="form-control" placeholder="Masukkan Jumlah Pembayaran" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Jumlah Pembayaran<span class="tx-danger">*</span></label>
                            <input type="number" name="amount_display" id="amount_display"
                                class="form-control input-display" placeholder="Masukkan Jumlah Pembayaran" required>
                            <input type="hidden" name="amount" id="amount" class="form-control input-raw"
                                placeholder="Masukkan Jumlah Pembayaran">
                        </div>
                        <div class="form-group">
                            <label for="tgl_bayar">Tanggal Pembayaran<span class="tx-danger">*</span></label>
                            <input type="date" name="tgl_bayar" id="tgl_bayar" class="form-control "
                                placeholder="Masukkan Tanggal Pembayaran" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran<span class="tx-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Bank Transfer">Transfer Bank</option>
                                <option value="Cash">Tunai</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Masukkan Catatan"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Tambah Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('input', '.input-display', function() {
                let $displayInput = $(this);
                let typedValue = $displayInput.val();

                let rawValue = typedValue.replace(/[^0-9]/g, '');

                let $rawInput = $displayInput.closest('.form-group').find('.input-raw');
                $rawInput.val(rawValue);

                if (rawValue) {
                    $displayInput.val(formatRibuan(rawValue));
                } else {
                    $displayInput.val('');
                }
            });
        });
        $('#btn-checkin-process').on('click', function(e) {
            e.preventDefault();
            var statusBooking = "{{ $data->booking_status }}";
            var statusPayment = "{{ $data->payment_status }}";
            var checkInData = "{{ $data->bookingRooms->first()->checkin_date }}";
            const today = new Date().toISOString().split('T')[0];
            var id = "{{ $data->id }}";
            if (statusPayment != 'Paid') {
                toastr.warning('Pembayaran belum lunas, harap menyelesaikan pembayaran');
            } else if (checkInData > today) {
                toastr.warning('Tanggal check in belum tiba, harap check in sesuai tanggal');
            } else {
                bookProcess('checkin');
            }
        });
        $('#btn-checkout-process').on('click', function(e) {
            e.preventDefault();
            bookProcess('checkout');
        });

        function bookProcess(type) {
            $.ajax({
                url: "{{ url('booking/process/' . $data->id) }}" + `/${type}`,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        function formatRibuan(angka) {
            if (!angka) return '';
            let numberString = angka.toString().replace(/[^,\d]/g, '');
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }

        $('.print-invoice').on('click', function(e) {
            e.preventDefault();
            var code = "{{ $data->invoices[0]->id }}";
            window.open("{{ url('booking/print-invoice') }}" + '/' + code, '_blank');
        })
    </script>
@endsection
