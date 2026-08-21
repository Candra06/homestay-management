<?php

namespace App\Exports;

use App\Models\Winner;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class WinnerExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $list = Winner::with('supplier')->orderBy('winner.created_at', 'DESC');
        $platform = isset($this->filters['platform']) ? $this->filters['platform'] : null;
        if ($platform != null && $platform != "") {
            $list->where('platform', $platform);
        }
        if (Auth::user()->roles->code == 'SUPPLIER') {
            $supplierId = Supplier::select('id')->where('user_id',Auth::user()->id)->first();
            $list->where('winner.supplier_id', $supplierId->id);
        }
        $start = isset($this->filters['start_date']) ? $this->filters['start_date'] : null;
        $end = isset($this->filters['end']) ? $this->filters['end'] : null;
        if ($start != null && $end != null && $start != "" && $end != "") {
            $list->whereBetween("winner.created_at", [$start . ' 00:00:00', $end . ' 23:59:59']);
        }
        $column = [
            'winner.platform',
            'winner.customer_name',
            'winner.user',
            'winner.unit_name',
            'winner.price',
            'winner.bayar',
            'winner.fee',
            'winner.extra_fee',
            'winner.description',
            'suppliers.name as supplier_name',
            'winner.status',
            'winner.created_at',
            'winner.address',
            'winner.city',

        ];
        if (Auth::user()->roles->code == 'SUPPLIER') {
            unset($column[12]);
            unset($column[13]);
        }
        if (Auth::user()->roles->code != 'SUPPLIER' && Auth::user()->roles->code != 'OWNER') {
            unset($column[6]);
            unset($column[7]);
            unset($column[8]);
        }
        // Select columns
        $list->select($column);

        // Join the supplier table
        $list->leftJoin('suppliers', 'winner.supplier_id', '=', 'suppliers.id');

        return $list->get();
    }

    public function headings(): array
    {
        $column = ['Platform', 'Nama', 'User', 'Unit', 'Price', 'Bayar','Fee','Extra Fee','Deskripsi Extra Fee', 'Supplier', 'Status', 'Tanggal', 'Alamat', 'Kota'];
        if (Auth::user()->roles->code == 'SUPPLIER') {
            unset($column[12]);
            unset($column[13]);
        }
        if (Auth::user()->roles->code != 'SUPPLIER' && Auth::user()->roles->code != 'OWNER') {
            unset($column[6]);
            unset($column[7]);
            unset($column[8]);
        }
        return $column;
    }
}
