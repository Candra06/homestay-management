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
            <tr>
                <td>BK001</td>
                <td>John Doe</td>
                <td>2</td>
                <td>15 Agustus 2026</td>
                <td>17 Agustus 2026</td>
                <td>Direct</td>
                <td><button class="btn btn-sm btn-success">Confirm</button></td>
                <td>
                    <a href="{{ route('booking.edit', 1) }}" class="btn btn-sm btn-primary mg-r-10">Edit</a>
                    <form action="{{ route('booking.destroy', 1) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td>BK002</td>
                <td>John Doe</td>
                <td>2</td>
                <td>15 Agustus 2026</td>
                <td>17 Agustus 2026</td>
                <td>Direct</td>
                <td><button class="btn btn-sm btn-warning">Pending</button></td>
                <td>
                    <a href="{{ route('booking.edit', 1) }}" class="btn btn-sm btn-primary mg-r-10">Edit</a>
                    <form action="{{ route('booking.destroy', 1) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td>BK001</td>
                <td>John Doe</td>
                <td>2</td>
                <td>15 Agustus 2026</td>
                <td>17 Agustus 2026</td>
                <td>Direct</td>
                <td><button class="btn btn-sm btn-danger">Cancel</button></td>
                <td>
                    <a href="{{ route('booking.edit', 1) }}" class="btn btn-sm btn-primary mg-r-10">Edit</a>
                    <form action="{{ route('booking.destroy', 1) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td>BK001</td>
                <td>John Doe</td>
                <td>2</td>
                <td>15 Agustus 2026</td>
                <td>17 Agustus 2026</td>
                <td>Direct</td>
                <td><button class="btn btn-sm btn-success">Confirm</button></td>
                <td>
                    <a href="{{ route('booking.edit', 1) }}" class="btn btn-sm btn-primary mg-r-10">Edit</a>
                    <form action="{{ route('booking.destroy', 1) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>
