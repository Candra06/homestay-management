<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\FinancialTransaction;
use App\Models\Rooms;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getCountReservation()
    {
        $start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end = Carbon::now()->endOfWeek()->format('Y-m-d');
        $lastDate = Carbon::now()->startOfMonth()->subMonth()->format('m'); 

        $thisWeek = Booking::where('created_at', '>', $start)
            ->where('created_at', '<', $end)->count();
        $lastData = Booking::whereRaw('MONTH(created_at) = ?', [$lastDate])->count();
        $data =(object)[
            'this_week' => $thisWeek,
            'last_month' => $lastData,
        ];
        return $data;

    }

    public function getCountCheckInCheckOut()
    {
        $checkIn = BookingRoom::where('checkin_date', Carbon::now()->format('Y-m-d'))->groupBy('booking_id')->count();
        $checkOut = BookingRoom::where('checkout_date', Carbon::now()->format('Y-m-d'))->groupBy('booking_id')->count();

        return [
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
        ];
    }

    public function getCountRoomAvailable($date)
    {
        $roomsQuery = Rooms::query();

        $rooms = $roomsQuery->with([
            'bookings',
            'bookings.booking' => function ($query) {
                $query->select('id', 'booking_status');
            },
        ])
            ->get()
            ->map(function ($room) use ($date) {

                $activeBooking = $room->bookings()
                    ->whereHas('booking', function ($q) {
                        $q->whereIn('booking_status', ['Pending', 'Approved', 'Checked-In']);
                    })
                    ->where(function ($query) use ($date) {
                        $query->where('checkin_date', '<=', $date)
                            ->where('checkout_date', '>=', $date);
                    })
                    ->with('booking')
                    ->first();

                if ($activeBooking) {
                    $bookingStatus = $activeBooking->booking->booking_status;
                    $target = date('Y-m-d', strtotime($date));
                    $checkin = date('Y-m-d', strtotime($activeBooking->checkin_date));
                    $checkout = date('Y-m-d', strtotime($activeBooking->checkout_date));
                    $status = '';
                    if (($target <= $checkin || $target > $checkout) && $activeBooking->booking->booking_code == null) {
                        $status = 'Tersedia';
                    } elseif ($bookingStatus == 'Approved' || $bookingStatus == 'Pending') {
                        $status = 'Booked';
                    } elseif ($bookingStatus == 'Checked-In') {
                        $status = 'Check-In';
                    }

                    $room->status = $status;
                    $room->booking_code = $activeBooking->booking->booking_code ?? '-';
                    $room->booking_status = $activeBooking->booking->booking_status ?? '-';
                } else {
                    $room->status = $room->status != 'Maintenance' ? 'Tersedia' : 'Maintenance';
                    $room->booking_code = null;
                    $room->booking_status = null;
                }

                return $room;
            });

        return [
            'total_rooms' => $rooms->count(),
            'available_rooms' => $rooms->where('status', 'Tersedia')->count(),
            'occupied_rooms' => $rooms->whereIn('status', ['Booked', 'Check-In'])->count(),
        ];
    }

    public function getCountIncome($date)
    {
        $query = FinancialTransaction::whereRaw('MONTH(transaction_date) = ?', [Carbon::parse($date)->month])
            ->where('transaction_type', 'income')
            ->sum('amount');
        $queryLast = FinancialTransaction::whereRaw('MONTH(transaction_date) = ?', [Carbon::parse($date)->subMonth()->month])
            ->where('transaction_type', 'income')
            ->sum('amount');

        return ['current_month' => $query, 'last_month' => $queryLast];
    }

    public function getNormalizedChartData()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $rawTransactions = FinancialTransaction::select(
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as `year_month`'),
            'transaction_type', // Asumsi kolom type isinya 'income' dan 'expense'
            DB::raw('SUM(amount) as total')
        )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('transaction_date', 'transaction_type')
            ->get()
            ->groupBy('year_month'); // Kelompokkan lagi berdasarkan bulannya

        // 3. Buat perulangan (looping) persis 12 bulan
        $period = CarbonPeriod::create($startDate, '1 month', $endDate);
        $chartData = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m'); // Contoh: "2025-12", "2026-01"
            $monthName = $date->translatedFormat('F Y'); // Contoh: "Desember 2025", "Januari 2026"

            // Ambil data transaksi di bulan tersebut berdasarkan key Y-m
            $monthlyData = $rawTransactions->get($key);

            // Cari total pemasukan (income) di bulan tersebut, jika tidak ada set 0
            $income = optional($monthlyData?->firstWhere('transaction_type', 'income'))->total ?? 0;

            // Cari total pengeluaran (expense) di bulan tersebut, jika tidak ada set 0
            $expense = optional($monthlyData?->firstWhere('transaction_type', 'expense'))->total ?? 0;

            // Masukkan ke dalam array hasil akhir (1 bulan = 1 objek data)
            $chartData[] = [
                'month' => $monthName,
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }
        return $chartData;
    }
    public function getNormalizedOccupancyChartData()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $rawTransactions = Booking::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as `year_month`'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotIn('booking_status', ['Cancelled','Pending'])
            ->groupBy('year_month')
            ->get()
            ->keyBy('year_month'); // Kelompokkan lagi berdasarkan bulannya
        
        // 3. Buat perulangan (looping) persis 12 bulan
        $period = CarbonPeriod::create($startDate, '1 month', $endDate);
        $chartData = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m'); // Contoh: "2025-12", "2026-01"
            $monthName = $date->translatedFormat('F Y'); // Contoh: "Desember 2025", "Januari 2026"
            
            // Ambil data transaksi di bulan tersebut berdasarkan key Y-m
            $monthlyData = $rawTransactions?->get($key);
            $total = $monthlyData ? $monthlyData->total : 0;


            // Masukkan ke dalam array hasil akhir (1 bulan = 1 objek data)
            $chartData[] = [
                'month' => $monthName,
                'total' => (float) $total,
            ];
        }
        return $chartData;
    }
}
