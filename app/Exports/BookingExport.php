<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class BookingExport implements FromCollection, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
{
    protected $start_date;

    protected $end_date;

    protected $source;

    protected $bookingStatus;

    protected $paymentStatus;

    public function __construct($req)
    {
        $this->start_date = $req->start_date ?? null;
        $this->end_date = $req->end_date ?? null;
        $this->source = $req->source ?? null;
        $this->bookingStatus = $req->booking_status ?? null;
        $this->paymentStatus = $req->payment_status ?? null;
    }

    public function collection()
    {
        $list = Booking::with([
            'guest' => function ($q) {
                $q->select('id', 'nama_lengkap');
            },
            'bookingRooms' => function ($q) {
                $q->select('id', 'booking_id', 'room_id', 'checkin_date', 'checkout_date');
            },
            'bookingRooms.room' => function ($q) {
                $q->select('id', 'room_number');
            },
            'invoices' => function ($q) {
                $q->select('id', 'booking_id', 'invoice_number');
            },
            'userCreate' => function ($q) {
                $q->select('id', 'name');
            }])
            ->orderBy('created_at', 'DESC');

        if (! empty($this->start_date) && ! empty($this->end_date)) {
            $list = $list->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }
        if (! empty($this->bookingStatus)) {
            $list = $list->where('booking_status', $this->bookingStatus);
        }
        if (! empty($this->paymentStatus)) {
            $list = $list->where('payment_status', $this->paymentStatus);
        }
        if (! empty($this->source)) {
            $list = $list->where('book_reff', $this->source);
        }
        $data = $list->get();

        // return response()->json($list->get());
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Ambil rentang kolom tertinggi (misal: 'A' sampai 'G')
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                // Loop setiap kolom dari kolom pertama (1) sampai terakhir
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);

                    // Set ukuran otomatis ditambah sedikit padding agar tidak terlalu mepet
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Check In',
            'Tanggal Check Out',
            'Tamu',
            'Kode Booking',
            'Status Booking',
            'Nomor Kamar',
            'Subtotal',
            'PPN',
            'Grand Total',
            'Total Pembayaran',
            'Total Discount',
            'Down Payment',
            'Kode Invoice',
            'Status Pembayaran',
            'Metode Pembayaran',
            'Tanggal Transaksi',
            'Front Office',
        ];
    }

    public function map($item): array
    {
        $room = [];
        $row = (object) $item;
        $roomNumbers = [];
        $checkinDate = '-';
        $checkoutDate = '-';
        $invoiceNumber = '-';
        $invoice = (object) $row->invoices;

        // 2. Cek apakah booking_rooms ada dan tidak kosong
        if (! empty($row->bookingRooms)) {

            $firstRoom = $row->bookingRooms[0] ?? null;

            if ($firstRoom) {
                $rawCheckin = is_array($firstRoom) ? ($firstRoom['checkin_date'] ?? null) : ($firstRoom->checkin_date ?? null);
                $rawCheckout = is_array($firstRoom) ? ($firstRoom['checkout_date'] ?? null) : ($firstRoom->checkout_date ?? null);

                // Ubah ke format teks Indonesia (Contoh: 10 November 2026)
                if ($rawCheckin) {
                    $checkinDate = Carbon::parse($rawCheckin)->translatedFormat('d F Y');
                }
                if ($rawCheckout) {
                    $checkoutDate = Carbon::parse($rawCheckout)->translatedFormat('d F Y');
                }
            }
            foreach ($row->bookingRooms as $val) {
                if (is_object($val)) {
                    $roomNumber = $val->room->room_number ?? null;
                    if ($roomNumber) {
                        $roomNumbers[] = $roomNumber;
                    }
                } else {
                    // Jika berupa array
                    $roomNumbers[] = $val['room']['room_number'] ?? '-';
                }
            }
        }
        if (! empty($row->invoices)) {
            $invoice = $row->invoices[0] ?? null;
            if ($invoice) {
                if (is_array($invoice)) {
                    $invoiceNumber = $invoice['invoice_number'] ?? '-';
                } else {
                    $invoiceNumber = $invoice->invoice_number ?? '-';
                }
            }

        }

        return [
            $checkinDate,
            $checkoutDate,
            $row->guest->nama_lengkap,
            $row->booking_code,
            $row->booking_status,
            implode(',', $roomNumbers),
            $row->subtotal,
            $row->tax,
            $row->grand_total,
            $row->total_payment,
            $row->discount_amount,
            $row->down_payment,
            $invoiceNumber,
            $row->payment_status,
            $row->payment_method,
            $row->created_at,
            $row->userCreate?->name,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'D MMMM YYYY',
            'B' => 'D MMMM YYYY',
            'G' => '"Rp. "#,##0',
            'I' => '"Rp. "#,##0',
            'J' => '"Rp. "#,##0',
            'K' => '"Rp. "#,##0',
            'L' => '"Rp. "#,##0',
        ];
    }
}
