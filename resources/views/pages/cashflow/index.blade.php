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
            <div class="pe-1 mb-xl-0">
                @if ($data->createBtn)
                    <a class="btn btn-primary p-0" ata-bs-effect="effect-scale" data-bs-toggle="modal"
                        href="#modal-add"><button class="btn btn-primary">
                            Tambah Transaksi</button></a>
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
                <div class="col-lg-4 col-xl-4 col-md-4 col-12">
                    <div class="card bg-success text-white ">
                        <div class="card-body">
                            <div class="mt-0 d-flex flex-row justify-content-start align-items-center">
                                <i class="fa fa-download text-white me-4" style="font-size: 30px;"></i>
                                <div>
                                    <span class="text-white">Pemasukan</span>
                                    <h3 class="text-white mb-0">{{ App\Helper\Helpers::rupiah($data->summary->income) }}
                                    </h3>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-4 col-md-4 col-12">
                    <div class="card bg-danger text-white ">
                        <div class="card-body">
                            <div class="mt-0 d-flex flex-row justify-content-start align-items-center">
                                <i class="fa fa-upload text-white me-4" style="font-size: 30px;"></i>
                                <div>
                                    <span class="text-white">Pengeluaran</span>
                                    <h3 class="text-white mb-0">{{ App\Helper\Helpers::rupiah($data->summary->expense) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-xl-4 col-md-4 col-12">
                    <div class="card bg-info text-white ">
                        <div class="card-body">
                            <div class="mt-0 d-flex flex-row justify-content-start align-items-center">
                                <i class="fa fa-money-bill-wave text-white me-4" style="font-size: 30px;"></i>
                                <div>
                                    <span class="text-white">Saldo</span>
                                    <h3 class="text-white mb-0">
                                        {{ App\Helper\Helpers::rupiah($data->summary->income - $data->summary->expense) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
    </div>
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
                            <input type="date" name="transaction_date" id="transaction_date" class="form-control "
                                placeholder="Masukkan Tanggal Transaksi" value="{{ old('transaction_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="transaction_type">Jenis Transaksi<span class="tx-danger">*</span></label>
                            <select name="transaction_type" id="transaction_type" class="form-control" required>
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="income" {{ old('transaction_type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="expense" {{ old('transaction_type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Nominal Transaksi<span class="tx-danger">*</span></label>
                            <input type="number" name="amount_display" id="amount_display"
                                class="form-control input-display" placeholder="Masukkan Nominal Transaksi" required>
                            <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}" class="form-control input-raw"
                                placeholder="Masukkan Nominal Transaksi">
                        </div>
                        <div class="form-group">
                            <label for="transaction_category">Kategori Transaksi<span class="tx-danger">*</span></label>
                            <select name="transaction_category" id="transaction_category" class="form-control" required>
                                <option value="">Pilih Kategori Transaksi</option>
                                @foreach ($data->category as $category)
                                    <option value="{{ $category->id }}" {{ old('transaction_category') == $category->id ? 'selected' : '' }}>{{ $category->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Masukkan Deskripsi" {{ old('description') }}></textarea>
                        </div>
                        <div class="form-group d-flex flex-row justify-content-end">
                            <button type="submit" class="btn btn-primary">Tambah Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#table").DataTable({
            ajax: '{{ $data->routeData }}',
            processing: true,
            serverSide: true,
            stateSave: true,
            columns: JSON.parse(`{!! json_encode($data->tableColumns) !!}`)
        });
    </script>
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


    <script>
        function deleteData(route, message) {
            $("#modal-delete").find("form").attr("action", route)
            $("#modal-delete").find(".message").text(message)
        }
    </script>
@endsection
