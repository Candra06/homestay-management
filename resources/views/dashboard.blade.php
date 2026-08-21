@extends('template.app')

@section('main')
    <!-- breadcrumb -->
    <x-alert />

    <div class="container-fluid">
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back!</h2>
                    <p class="mg-b-0">Dashboard Ezzy Homestay.</p>
                </div>
            </div>
            <div class="right-content text-right">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">{{ date('D,d M Y') }}</h2>
                    <p class="mg-b-0">{{ date('H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white">PENDAPATAN HARI INI</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 fw-bold mb-1 text-white">$5,74.12</h4>
                                    <p class="mb-0 tx-12 text-white op-7">Compared to last week</p>
                                </div>
                                <span class="float-end my-auto ms-auto">
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white op-7"> +427</span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-danger-gradient">
                    <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white">PENDAPATAN BULAN INI</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 fw-bold mb-1 text-white">$1,230.17</h4>
                                    <p class="mb-0 tx-12 text-white op-7">Compared to last week</p>
                                </div>
                                <span class="float-end my-auto ms-auto">
                                    <i class="fas fa-arrow-circle-down text-white"></i>
                                    <span class="text-white op-7"> -23.09%</span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white">GUEST CHECK-IN</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 fw-bold mb-1 text-white">$7,125.70</h4>
                                    <p class="mb-0 tx-12 text-white op-7">Tamu hari ini</p>
                                </div>
                                <span class="float-end my-auto ms-auto">
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white op-7"> 52.09%</span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white">KAMAR TERSEDIA</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 fw-bold mb-1 text-white">$4,820.50</h4>
                                    <p class="mb-0 tx-12 text-white op-7">Kamar tersedia hari ini</p>
                                </div>
                                <span class="float-end my-auto ms-auto">
                                    <i class="fas fa-arrow-circle-down text-white"></i>
                                    <span class="text-white op-7"> -152.3</span>
                                </span>
                            </div>
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
                    <div class="card-body">
                        <div class="total-revenue">
                            <div>
                                <h4>120,750</h4>
                                <label><span class="bg-primary"></span>Pemasukan</label>
                            </div>
                            <div>
                                <h4>56,108</h4>
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
                    <div class="card-body">
                        <div class="total-revenue">
                            <div>
                                <h4>120,750</h4>
                                <label><span class="bg-primary"></span>Terkonfirmasi</label>
                            </div>
                            <div>
                                <h4>56,108</h4>
                                <label><span class="bg-danger"></span>Batal</label>
                            </div>
                        </div>
                        <div id="bar2" class="sales-bar mt-4"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-sm">
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

        </div>

    </div>
@endsection



@section('script')
    <script src="{{ asset('assets') }}/js/apexcharts.js"></script>
    <script>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> --}}
@endsection
