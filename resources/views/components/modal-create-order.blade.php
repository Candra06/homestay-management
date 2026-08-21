<div class="modal fade" id="modal-create-order">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Penjemputan Barang</h6><button aria-label="Close" class="close"
                    data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="create-order">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <label for="">Tanggal Penjemputan</label>
                            <input type="date" class="form-control" id="date" name="date-order">
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-2">
                            <label for="">Jam Penjemputan</label>
                            <select name="time-order" id="time" id="volume-type" class="form-control">
                                <option value="">Pilih Jam Penjemputan</option>
                                @for ($i = 9; $i < 22; $i++)
                                    <option value="{{ $i < 10 ? '0' . $i : $i }}:00">{{ $i < 10 ? '0' . $i : $i }}:00</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-2">
                            <label for="">Tipe Penjemputan</label>
                            <select name="volume-type" id="volume-type" id="volume-type" class="form-control">
                                <option value="">Pilih Volume</option>
                                <option value="volumeTruck">Truk</option>
                                <option value="volumeMobil">Mobil</option>
                                <option value="volumeMotor">Motor</option>
                            </select>

                        </div>
                        {{-- <div class="col-md-12 col-lg-12 col-sm-12 mt-4">
                            <h6>Total Produk</h6>
                            <h6><strong>{{count($product)}}</strong></h6>
                        </div> --}}
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-4">
                            <div class="d-flex justify-content-between">
                                <h6>Rincian Pengiriman</h6>
                                <a id="show-detail" style="cursor: pointer;">Detail</a>
                            </div>
                            <div class="d-none" id="list-produk">

                                <table class="table table-striped mg-b-0 text-md-nowrap" id="list-shipping">
                                    <thead>

                                        <tr>
                                            <th>Nama Customer</th>
                                            <th>Total Produk</th>
                                            <th>Total Harga</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    {{-- @foreach ($product as $item)
                                        <tr>
                                            <td>
                                                <div
                                                    style="display:flex; min-width: 100px;max-width:400px;font-size: 13px;align-items: center;">
                                                    <img data-bs-effect="effect-scale" data-bs-toggle="modal"
                                                        href="#modal-thumbnail"
                                                        src="{{ $item->thumbnail ?? 'https://sukalelang.id/wp-content/uploads/woocommerce-placeholder-600x600.png' }}"
                                                        class="img-product" alt="'.$row->unit_name.'" />
                                                    <div class="column-product">
                                                        <a target="_blank"
                                                            href="{{ $item->url_product }}">{{ $item->unit_name }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ App\Helper\Helpers::rupiah($item->price, 'Rp. ') }}</td>
                                            <td><input type="checkbox" id="product_id" name="product_id[]" value="{{$item->id}}"></td>
                                        </tr>
                                    @endforeach --}}
                                </table>
                            </div>

                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-2 d-flex justify-content-between">
                            <div></div>
                            <button type="button" class="btn btn-primary " id="request-shipping">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
