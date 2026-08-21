@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->subtitle }} </span><span class="text-muted mt-1 tx-13 ms-2 mb-0">/
                    {{ $data->title }} </span>

            </div>

        </div>

    </div>

    <x-alert />

    <div class="card box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $data->title }}</h4>
            <div class="card-body p-0 mt-3">
                <form action="{{url('wording-master')}}" enctype="multipart/form-data"
                    class="form-horizontal row" method="post">
                    @csrf
                    <div class="form-group has-success col-md-6 col-12">
                        <label for="reminder">Reminder</label>
                        <textarea class="form-control" id="reminder" rows="5"
                        placeholder="Konten Reminder"
                        required name="reminder" >{{ $data->data->reminder }}</textarea>
                        <small>Template akan digunakan saat mulai lelang</small>
                        @error('reminder')
                        <small class=" text-danger">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="form-group has-success col-md-6 col-12">
                        <label for="reminder">Informasi</label>
                        <textarea class="form-control" id="information" rows="5"
                        placeholder="Konten information" required name="information" >{{ $data->data->information }}</textarea>

                        <small>Template akan digunakan saat lelang selesai kepada pemenang lelang</small>
                        @error('information')
                        <small class=" text-danger">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label class="text-sm  text-slate-700 dark:text-slate-400 ">Note : </label>
                        <ul>

                            <li>Gunakan <code>#nama</code> untuk menyisipkan Nama Pemenang secara otomatis</li>
                            <li>Gunakan <code>#produk</code> untuk menyisipkan Nama Produk secara otomatis</li>
                            <li>Gunakan <code>#payment</code> untuk menyisipkan Link Pembayaran kepada customer secara otomatis</li>
                            {{-- <li>Gunakan <code>#link</code> untuk menyisipkan URL Produk secara otomatis</li> --}}

                        </ul>
                    </div>
                    <div class="form-group has-success col-12 mb-0 mt-3 justify-content-end">
                        <div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
