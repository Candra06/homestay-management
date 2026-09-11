<div class="table-responsive table-view">
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
     <x-modal-delete />
</div>
