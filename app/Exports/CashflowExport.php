<?php

namespace App\Exports;

use App\Helper\Helpers;
use App\Models\FinancialTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CashflowExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $start;
    protected $end;

    public function __construct($start = null, $end = null) {
        $this->start = $start;
        $this->end = $end;
    }
    public function collection()
    {
        $query = FinancialTransaction::with([
                'account' => function ($q) {
                    $q->select('id', 'account_code', 'account_name', 'type', 'balance')->get();
                },
                
                'booking' => function ($q) {
                    $q->select('id','booking_code', 'guest_id')->get();
                },
                'booking.guest' => function ($q) {
                    $q->select('id','nama_lengkap')->get();
                },
                'booking.bookingRooms' => function ($q) {
                    $q->select('id','booking_id', 'checkin_date', 'checkout_date', 'room_id')->get();
                },
                'booking.bookingRooms.room' => function ($q) {
                    $q->select('id','room_number')->get();
                },
                'user'])->whereBetween('transaction_date', [$this->start, $this->end])
                ->get();
        return $query;
    }

    public function headings() : array {
        return [
            'Tanggal Transaksi',
            'Tanggal Check In',
            'Jenis Transaksi',
            'Kategori',
            'Sumber Dana',
            'Catatan',
            'Tamu',
            'Petugas',
            'Nominal',
            'Metode Pembayaran',
        ];
    }

    public function map($item) : array {
        return [
            $item->transaction_date,
            $item->reference_type == 'booking' ? (empty($item->booking->bookingRooms) ? '-' :$item->booking->bookingRooms[0]->checkin_date) : '-',
            $item->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran',
            $item->account->account_name,
            $item->account->account_code == '1-100' ? 'Reservasi' : $item->account->account_name,
            $item->description,
            $item->booking->guest->nama_lengkap ?? '-',
            $item->user->name,
            $item->amount,
            $item->payment_method??'-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            // Kolom G (Nominal) diformat menjadi mata uang Rupiah secara native oleh Excel
            'G' => '"Rp"#,##0', 
        ];
    }
}
