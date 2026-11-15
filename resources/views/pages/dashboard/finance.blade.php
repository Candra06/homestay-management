@extends('template.app')
@section('css')
    <link href="{{ asset('assets') }}/css/animate.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('main')
    <!-- breadcrumb -->
    <x-alert />
    @php
        $summary = (object) $data->summary;
        $income = (object) $summary->income;
        $expense = (object) $summary->expense;
        $net_profit = (object) $summary->net_profit;
        $balance = (object) $summary->balance;
    @endphp
    <div class="container-fluid">
        <div class="breadcrumb-header justify-content-between mb-0">
            <div class="left-content">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1 mt-2">Laporan Keuangan & Arus Kas</h2>

                </div>
            </div>
            <div class="right-content " style="width: 18% !important;">
                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-text" style="padding: 4px 0px">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="text" id="daterange" placeholder="Pilih periode" name="daterange"
                            class="form-control" value="" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body iconfont text-start">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">Pendapatan Bulan Ini</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="d-flex mb-0">
                            <div class="">
                                <h3 class="mb-1 fw-bold income-total">{{ App\Helper\Helpers::rupiah($income->current_month) }} <i
                                        class="{{ $income->growth_percentage > 0 ? 'text-success fa fa-arrow-up' : 'text-danger fa fa-arrow-down' }}"></i>
                                </h3>

                            </div>
                        </div>
                        <small class="mb-0  text-muted income-last-month">Bulan lalu<span class="float-end text-muted ">
                                {{ App\Helper\Helpers::rupiah($income->last_month) }}</span></small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body iconfont text-start">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">Pengeluaran Bulan Ini</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="d-flex mb-0">
                            <div class="">
                                <h3 class="mb-1 fw-bold expanse-total">{{ App\Helper\Helpers::rupiah($expense->current_month) }} <i
                                        class="{{ $expense->growth_percentage > 0 ? 'text-success fa fa-arrow-up' : 'text-danger fa fa-arrow-down' }}"></i>
                                </h3>

                            </div>
                        </div>
                        <small class="mb-0  text-muted expanse-last-month">Bulan lalu<span class="float-end text-muted ">
                                {{ App\Helper\Helpers::rupiah($expense->last_month) }}</span></small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body iconfont text-start">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">Laba Bersih</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="d-flex mb-3 pb-1">
                            <div class="">
                                <h3 class="mb-1 fw-bold">{{ App\Helper\Helpers::rupiah($net_profit->current_month) }} </h3>

                            </div>
                        </div>
                        <small class="mb-0  text-muted"></small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card ">
                    <div class="card-body iconfont text-start">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">Saldo Bulan Ini</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="d-flex mb-3 pb-1">
                            <div class="">
                                <h3 class="mb-1 fw-bold">{{ App\Helper\Helpers::rupiah($balance->current_month) }} </h3>

                            </div>
                        </div>
                        <small class="mb-0  text-muted"></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-md-12 col-lg-12 col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-0">Tren Pemasukan Vs Pengeluaran</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                        <p class="tx-12 text-muted mb-0">Periode 6 bulan terakhir</p>
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
                            <h4 class="card-title mb-0">Arus kas</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                        <p class="tx-12 text-muted mb-0">Perkembangan Arus Kas 12 Bulan Terakhir</p>
                    </div>
                    <div id="loading-cashflow">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div>
                            <p class="ms-2 tx-center">Memuat Data...</p>
                        </div>
                    </div>
                    <div id="chart-cashflow" class="card-body">
                        <div class="total-revenue">
                            <div>
                                <h4 id="totalCashIn">0</h4>
                                <label><span class="bg-success"></span>Kas Masuk</label>
                            </div>
                            <div>
                                <h4 id="totalCashOut">0</h4>
                                <label><span class="bg-danger"></span>Kas Keluar</label>
                            </div>
                        </div>
                        <div id="bar2" class="sales-bar mt-4"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3">Log Transaksi Terakhir</h4>
                            <i class="mdi mdi-dots-vertical"></i>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mg-b-0 text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="tx-10">Tanggal Transaksi</th>
                                            <th class="tx-10">Jenis Transaksi</th>
                                            <th class="tx-10">Sumber</th>
                                            <th class="tx-10">Kategori</th>
                                            <th class="tx-10">Nominal</th>
                                            <th class="tx-10">Metode Bayar</th>
                                            <th class="tx-10">Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->history as $item)
                                            <tr>
                                                <td class="tx-10">{{ App\Helper\Helpers::tanggal($item->transaction_date) }}</td>
                                                <td class="tx-10">{{ $item->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                                                <td class="tx-10">{{ $item->account->account_code == '1-100' ? 'Reservasi' : 'Lain-lain' }}</td>
                                                <td class="tx-10">{{ $item->account->account_name }}</td>
                                                <td class="tx-10">{{ App\Helper\Helpers::rupiah($item->amount) }}</td>
                                                <td class="tx-10">{{ $item->payment_method }}</td>
                                                <td class="tx-10">{{ $item->user->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection



@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets') }}/js/apexcharts.js"></script>
    <script>
        var incomeExpanseChart = null,
            cashFlowChart = null;
        $(document).ready(function() {
            // getFinanceChart();
            // getCashFlowChart();
            loadData();
        });


        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                autoUpdateInput: false, // Biarkan kosong dulu sampai user memilih
                alwaysShowCalendars: true, // <--- Kunci utama agar kalender selalu tampil di sisi kanan
                showCustomRangeLabel: true,
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Minggu Ini': [moment().startOf('week'), moment().endOf('week')],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                    '1 Tahun Terakhir': [moment().subtract(1, 'year'), moment()]
                },
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' s/d ',
                    applyLabel: 'Pilih',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Hingga',
                    customRangeLabel: 'Pilih Sendiri',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                }
            }, function(start, end, label) {
                // Callback saat tanggal dipilih
                $('#daterange').val(start.format('YYYY-MM-DD') + ' s/d ' + end.format('YYYY-MM-DD'));
                loadData();

            });

            // Handle tombol batal agar input kembali bersih
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        function loadData() {
            $("#loading-finance").show();
            $("#chart-finance").hide();
            $("#loading-cashflow").show();
            $("#chart-cashflow").hide();
            let param = {};
            var daterange = $('#daterange').val();
            var typeTransaction = $('#type_transaction').val();
            if (daterange) {
                var dates = daterange.split(
                    ' s/d '); // Sesuaikan separator dengan settingan daterangepicker kamu
                param.start_date = dates[0];
                param.end_date = dates[1];
            }
            if (typeTransaction) {
                param.type_transaction = typeTransaction
            }
            $.ajax({
                url: "{{ url('financial-report') }}",
                type: "GET",
                data: param,
                dataType: "JSON",
                success: function(response) {
                    data = response.data;

                    $('#totalIncome').text('Rp. ' + formatRibuan(data.cashFlowIncomeExpense[data
                            .cashFlowIncomeExpense.length - 1].income
                        .toLocaleString('id-ID')));
                    $('#totalExpense').text('Rp. ' + formatRibuan(data.cashFlowIncomeExpense[data
                            .cashFlowIncomeExpense.length - 1]
                        .expense.toLocaleString('id-ID')));
                    $('#totalCashIn').text('Rp. ' + formatRibuan(data.cashFlowMonthly[data
                            .cashFlowMonthly.length - 1].cash_in
                        .toLocaleString('id-ID')));
                    $('#totalCashOut').text('Rp. ' + formatRibuan(data.cashFlowMonthly[data
                            .cashFlowMonthly.length - 1]
                        .cash_out.toLocaleString('id-ID')));
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function(response) {
                    $("#loading-finance").hide();
                    $("#chart-finance").show();
                    $("#loading-cashflow").hide();
                    $("#chart-cashflow").show();

                    renderFinanceChart(data.cashFlowIncomeExpense);
                    renderCashFlowChart(data.cashFlowMonthly);
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
                yaxis: {
                    labels: {
                        formatter: function(val) {
                           if (val === undefined || val === null) {
                                return "0";
                           }
                           return formatRibuan(val);
                        }
                    }
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
            if (incomeExpanseChart) {
                incomeExpanseChart.destroy();
            }
            $('#bar').empty();
            incomeExpanseChart = new ApexCharts(document.querySelector('#bar'), optionsBar);
            incomeExpanseChart.render();
            /* Apexcharts (#bar) closed */
        }

        function renderCashFlowChart(data) {
            /* Apexcharts (#bar) */
            let cashIn = [];
            let cashOut = [];
            let category = [];
            $.each(data, function(index, value) {
                cashIn.push(value.cash_in);
                cashOut.push(value.cash_out);
                category.push(value.month);
            });
            var cashflowBar = {
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
                colors: ["#36ae7c", '#dc3545'],
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: false
                        },
                        columnWidth: '42%',
                        
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    endingShape: 'square',
                    colors: ['transparent'],
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        stroke: {
                            show: true,
                            width: 1,
                            endingShape: 'square',
                            colors: ['transparent'],
                        },
                    },


                }],
                series: [{
                        name: 'Kas Masuk',
                        data: cashIn
                    },
                    {
                        name: 'Kas Keluar',
                        data: cashOut
                    },
                ],
                xaxis: {
                    categories: category
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return formatRibuan(val)
                        }
                    }
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
            if (cashFlowChart) {
                cashFlowChart.destroy();
            }
            cashFlowChart = new ApexCharts(document.querySelector('#bar2'), cashflowBar).render();

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
