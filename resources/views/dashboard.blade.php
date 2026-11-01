@extends('template.app')

@section('main')
    <!-- breadcrumb -->
    <x-alert />

    <div class="container-fluid">
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, selamat datang!</h2>
                    <p class="mg-b-0">Dashboard Ezzy Homestay.</p>
                </div>
            </div>
            <div class="right-content text-right">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">
                        {{ Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</h2>
                    <p class="mg-b-0">{{ date('H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body iconfont text-start">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">okupansi minggu ini</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="d-flex mb-0">
                            <div class="">
                                <h3 class="mb-1 fw-bold">{{ $data->occupancyWeekly }}</h3>

                            </div>
                        </div>
                        <small class="mb-0  text-muted">Bulan lalu<span class="float-end text-muted">80</span></small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="card-title pb-0 tx-12">check-in/check-out hari ini</div>
                    </div>
                    <div class="card-body pt-1 pb-0">
                        <div class="row">
                            <div class="col text-center text-success">
                                <label class="tx-12">Check-in</label>
                                <p class="fw-bold tx-20">{{ $data->checkInCheckOut['checkIn'] }}</p>
                            </div><!-- col -->
                            <div class="col border-start text-center text-danger">
                                <label class="tx-12">Check-out</label>
                                <p class="fw-bold tx-20">{{ $data->checkInCheckOut['checkOut'] }}</p>
                            </div><!-- col -->

                        </div><!-- row -->
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="card-title pb-0">Ketersediaan kamar hari ini</div>
                    </div>
                    <div class="card-body pt-1 pb-0">
                        <div class="row">
                            <div class="col text-center text-success">
                                <label class="tx-12">Tersedia</label>
                                <p class="fw-bold tx-20">{{ $data->roomAvailable['available_rooms'] }}</p>
                            </div><!-- col -->
                            <div class="col border-start text-center text-danger">
                                <label class="tx-12">Tidak tersedia</label>
                                <p class="fw-bold tx-20">{{ $data->roomAvailable['occupied_rooms'] }}</p>
                            </div><!-- col -->

                        </div><!-- row -->
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="card-widget">
                            <h6 class=" card-title mb-2">Pendapatan Bulan ini</h6>
                            <h3 class="text-end pt-1"><i
                                    class="fas fa-dollar-sign icon-size float-start text-success text-success-shadow"></i><span
                                    class="text-success fw-bold">{{ App\Helper\Helpers::rupiah($data->income['current_month']) }}</span>
                            </h3>
                            <p class="mb-0 pt-1 ">Bulan lalu<span
                                    class="float-end">{{ App\Helper\Helpers::rupiah($data->income['last_month']) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-md-12 col-lg-12 col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-0">Keuangan Bulanan</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                        <p class="tx-12 text-muted mb-0">Pengeluaran dan pemasukan bulanan</p>
                    </div>
                    <div id="loading-finance">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div>
                            <p class="ms-2 tx-center">Memuat Data...</p>
                        </div>
                    </div>
                    <div id="chart-finance" class="card-body">
                        <div class="total-revenue">
                            <div>
                                <h4 id="totalIncome">0</h4>
                                <label><span class="bg-primary"></span>Pemasukan</label>
                            </div>
                            <div>
                                <h4 id="totalExpense">0</h4>
                                <label><span class="bg-danger"></span>Pengeluaran</label>
                            </div>
                        </div>
                        <div id="bar" class="sales-bar mt-4"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-12 col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-0">Okupansi Bulanan</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                        <p class="tx-12 text-muted mb-0">Jumlah reservasi bulanan</p>
                    </div>
                    <div id="loading-occupancy">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div>
                            <p class="ms-2 tx-center">Memuat Data...</p>
                        </div>
                    </div>
                    <div id="chart-occupancy" class="card-body">
                        <div class="total-revenue">
                            <div>
                                <h4 id="totalOccupancy">0</h4>
                                <label><span class="bg-primary"></span>Transaksi Reservasi</label>
                            </div>

                        </div>
                        <div id="bar2" class="sales-bar mt-4"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">Aksi Cepat</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="card-body p-0">
                            <a href="{{ route('booking.create') }}" class="btn btn-primary btn-block "><i
                                    class="fas fa-calendar-check"></i> Reservasi Baru</a>
                            <a href="{{ url('room/list/') . '/all' }}" class="btn btn-info btn-block "><i
                                    class="fas fa-bed"></i> Ketersediaan Kamar</a>
                            <a ata-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-add"
                                class="btn btn-success btn-block "><i class="fas fa-plus"></i> Tambah
                                Transaksi</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row row-sm">
            <div class="col-xl-6 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header pb-1">
                        <h3 class="card-title mb-2">Reservasi Terkini</h3>
                        <p class="tx-12 mb-0 text-muted"></p>
                    </div>
                    <div class="card-body p-0 customers mt-1">
                        <div class="list-group list-lg-group list-group-flush">
                            <div class="list-group-item list-group-item-action" href="#">
                                <div class="media mt-0">

                                    <div class="media-body">
                                        <div class="d-flex align-items-between">
                                            <div class="mt-0">
                                                <h5 class="mb-1 tx-15">Samantha Melon</h5>
                                                <p class="mb-0 tx-13 text-muted">Kode Booking: #1234 <span
                                                        class="text-success ms-2">Paid</span></p>
                                            </div>
                                            <div class="mt-0 ms-auto text-right">
                                                <h5 class="mb-1 tx-15">Deluxe (201)</h5>
                                                <p class="mb-0 tx-13 text-muted">Direct Web</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action br-t-1" href="#">
                                <div class="media mt-0">

                                    <div class="media-body">
                                        <div class="d-flex align-items-space-between">
                                            <div class="mt-1">
                                                <h5 class="mb-1 tx-15">Jimmy Changa</h5>
                                                <p class="mb-0 tx-13 text-muted">Kode Booking: #1234 <span
                                                        class="text-danger ms-2">Pending</span></p>
                                            </div>
                                            <div class="mt-0 ms-auto text-right">
                                                <h5 class="mb-1 tx-15">Standart (101)</h5>
                                                <p class="mb-0 tx-13 text-muted">Ticket.com</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item list-group-item-action br-t-1" href="#">
                                <div class="media mt-0">

                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="mt-1">
                                                <h5 class="mb-1 tx-15">Gabe Lackmen</h5>
                                                <p class="mb-0 tx-13 text-muted">Kode Booking: #1234<span
                                                        class="text-danger ms-2">Pending</span></p>
                                            </div>
                                            <div class="mt-0 ms-auto text-right">
                                                <h5 class="mb-1 tx-15">VIP (101)</h5>
                                                <p class="mb-0 tx-13 text-muted">Ticket.com</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> --}}
        <div class="modal fade" id="modal-add">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Tambah Transaksi</h6><button aria-label="Close" class="close"
                            data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/cashflow') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="transaction_date">Tanggal Transaksi<span class="tx-danger">*</span></label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                    class="form-control " placeholder="Masukkan Tanggal Transaksi"
                                    value="{{ old('transaction_date') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="transaction_type">Jenis Transaksi<span class="tx-danger">*</span></label>
                                <select name="transaction_type" id="transaction_type" class="form-control" required>
                                    <option value="">Pilih Jenis Transaksi</option>
                                    <option value="income" {{ old('transaction_type') == 'income' ? 'selected' : '' }}>
                                        Pemasukan</option>
                                    <option value="expense" {{ old('transaction_type') == 'expense' ? 'selected' : '' }}>
                                        Pengeluaran</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Metode Pembayaran<span class="tx-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="">Pilih Metode Bayar</option>
                                    <option value="Cash">Tunai</option>
                                    <option value="QRIS BCA">QRIS BCA</option>
                                    <option value="QRIS BNI">QRIS BNI</option>
                                    <option value="Transfer BCA">Transfer BCA</option>
                                    <option value="Transfer BNI">Transfer BNI</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Nominal Transaksi<span class="tx-danger">*</span></label>
                                <input type="number" name="amount_display" id="amount_display"
                                    class="form-control input-display" placeholder="Masukkan Nominal Transaksi" required>
                                <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}"
                                    class="form-control input-raw" placeholder="Masukkan Nominal Transaksi">
                            </div>
                            <div class="form-group">
                                <label for="transaction_category">Kategori Transaksi<span
                                        class="tx-danger">*</span></label>
                                <select name="transaction_category" id="transaction_category" class="form-control"
                                    required>
                                    <option value="">Pilih Kategori Transaksi</option>
                                    @foreach ($data->category as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('transaction_category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Masukkan Deskripsi"
                                    {{ old('description') }}></textarea>
                            </div>
                            <div class="form-group d-flex flex-row justify-content-end">
                                <button type="submit" class="btn btn-primary">Tambah Transaksi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
    <script src="{{ asset('assets') }}/js/apexcharts.js"></script>
    <script>
        $(document).ready(function() {
            getFinanceChart();
            getOccupancyChart();
        });

        function getFinanceChart() {
            $("#loading-finance").show();
            $("#chart-finance").hide();
            let data = {};
            $.ajax({
                url: "{{ url('dashboard-finance') }}",
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    data = response.data;
                    $('#totalIncome').text('Rp. ' + formatRibuan(response.data[response.data.length - 1].income
                        .toLocaleString('id-ID')));
                    $('#totalExpense').text('Rp. ' + formatRibuan(response.data[response.data.length - 1]
                        .expense.toLocaleString('id-ID')));
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function(response) {
                    $("#loading-finance").hide();
                    $("#chart-finance").show();

                    renderFinanceChart(data);
                }
            })
        }

        function getOccupancyChart() {
            $("#loading-occupancy").show();
            $("#chart-occupancy").hide();
            let data = {};
            $.ajax({
                url: "{{ url('dashboard-occupancy') }}",
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    data = response.data;
                    $('#totalOccupancy').text(response.data[response.data.length - 1].total);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function() {
                    $("#loading-occupancy").hide();
                    $("#chart-occupancy").show();
                    renderOccupancyChart(data);
                }
            })
        }

        function renderFinanceChart(data) {
            /* Apexcharts (#bar) */
            let income = [];
            let outcome = [];
            let category = [];
            $.each(data, function(index, value) {
                income.push(value.income);
                outcome.push(value.expense);
                category.push(value.month);
            });

            var optionsBar = {
                chart: {
                    height: 249,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                    fontFamily: 'Nunito, sans-serif',
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 2,
                        opacity: 0.2,
                    }
                },
                colors: ["#036fe7", '#f93a5a'],
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: false
                        },
                        columnWidth: '42%',
                        endingShape: 'rounded',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    endingShape: 'rounded',
                    colors: ['transparent'],
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        stroke: {
                            show: true,
                            width: 1,
                            endingShape: 'rounded',
                            colors: ['transparent'],
                        },
                    },


                }],
                series: [{
                    name: 'Pemasukan',
                    data: income
                }, {
                    name: 'Pengeluaran',
                    data: outcome
                }, ],
                xaxis: {
                    categories: category,
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    show: false,
                    floating: true,
                    position: 'top',
                    horizontalAlign: 'left',
                },

                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp. " + formatRibuan(val)
                        }
                    }
                }
            }
            new ApexCharts(document.querySelector('#bar'), optionsBar).render();
            /* Apexcharts (#bar) closed */
        }

        function renderOccupancyChart(data) {
            /* Apexcharts (#bar) */
            let occupied = [];
            let category = [];
            $.each(data, function(index, value) {
                occupied.push(value.total);
                category.push(value.month);
            });
            var ocupancyBar = {
                chart: {
                    height: 249,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                    fontFamily: 'Nunito, sans-serif',
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 2,
                        opacity: 0.2,
                    }
                },
                colors: ["#036fe7", '#f93a5a'],
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: false
                        },
                        columnWidth: '42%',
                        endingShape: 'rounded',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    endingShape: 'rounded',
                    colors: ['transparent'],
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        stroke: {
                            show: true,
                            width: 1,
                            endingShape: 'rounded',
                            colors: ['transparent'],
                        },
                    },


                }],
                series: [{
                    name: 'Pemesanan',
                    data: occupied
                }, ],
                xaxis: {
                    categories: category
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    show: false,
                    floating: true,
                    position: 'top',
                    horizontalAlign: 'left',
                },

                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val
                        }
                    }
                }
            }
            new ApexCharts(document.querySelector('#bar2'), ocupancyBar).render();

            /* Apexcharts (#bar) closed */
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
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> --}}
@endsection
