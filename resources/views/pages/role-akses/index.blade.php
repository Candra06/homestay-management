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
                <form action="{{$data->action}}" enctype="multipart/form-data"
                    class="form-horizontal m-2" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="">
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                    <tr>

                                        <th>Menu</th>
                                        <th class="text-center">List</th>
                                        <th class="text-center">Create</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->data as $item)
                                <tr>
                                    <td>
                                        {{$item->menu->name}}
                                        <input type="hidden" name="id_menu[]" value="{{$item->id_menu}}">
                                    </td>
                                    <td class="text-center">
                                        @if ($item->menu->have_list == 'Y')
                                            <input type="checkbox" class="form-check-input" value="Y" onchange="handleCheckboxChange(this.id, this.checked)"  id="access_list-{{$item->id}}" name="access_list-{{$item->menu->id}}" {{$item->access_list == 'Y' ? 'checked' :''}} />
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->menu->have_create == 'Y')
                                            <input type="checkbox" class="form-check-input" value="Y" onchange="handleCheckboxChange(this.id, this.checked)"  id="access_create-{{$item->id}}" name="access_create-{{$item->menu->id}}" {{$item->access_create == 'Y' ? 'checked' :''}} />
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->menu->have_edit == 'Y')

                                            <input type="checkbox" class="form-check-input" value="Y" onchange="handleCheckboxChange(this.id, this.checked)"  id="access_edit-{{$item->id}}" name="access_edit-{{$item->menu->id}}" {{$item->access_edit == 'Y' ? 'checked' :''}} />
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->menu->have_delete == 'Y')
                                            <input type="checkbox" class="form-check-input" value="Y" onchange="handleCheckboxChange(this.id, this.checked)"  id="access_delete-{{$item->id}}" name="access_delete-{{$item->menu->id}}" {{$item->access_delete == 'Y' ? 'checked' :''}} />
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group has-success mb-0 mt-3 text-end">
                        <div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function handleCheckboxChange(id, isChecked) {
            const value = isChecked ? 'Y' : 'N';
            console.log(`Checkbox ID: ${id}, Value: ${value}`);
            $('#'+id).val(value);
            if (value == 'Y') {

                $('#'+id).prop('checked', true);
            } else {
                $('#'+id).prop('checked', false);
            }
        // You can send this value to your server or process it further
        }
    </script>
@endsection
