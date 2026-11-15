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

    /*
    |--------------------------------------------------------------------------
    | FUNGSI-FUNGSI LAPORAN KEUANGAN & ARUS KAS (FINANCIAL REPORT)
    |--------------------------------------------------------------------------
    | Sesuai standar akuntansi kas (cash-basis) berdasarkan data financial_transactions
    | Mendukung filter periodik (tanggal tunggal, rentang tanggal, atau default bulan ini)
    */

    /**
     * Menghitung ringkasan (summary) keuangan:
     * 1. Pendapatan bulan/periode ini vs bulan sebelumnya sebagai pembanding (+ % pertumbuhan)
     * 2. Pengeluaran bulan/periode ini vs bulan sebelumnya sebagai pembanding (+ % pertumbuhan)
     * 3. Laba bersih bulan/periode ini (Pendapatan - Pengeluaran)
     * 4. Saldo bulan/periode ini (Net periode ini & Saldo Kas Kumulatif)
     *
     * @param string|null $dateOrStartDate Tanggal referensi ("YYYY-MM-DD") atau string rentang ("YYYY-MM-DD s/d YYYY-MM-DD")
     * @param string|null $endDate Tanggal akhir jika menggunakan 2 parameter terpisah
     * @return object
     */
    public function getFinancialReportSummary($dateOrStartDate = null, $endDate = null)
    {
        // 1. Tentukan tanggal periode aktif dan periode pembanding secara periodik
        $period = $this->resolvePeriodDates($dateOrStartDate, $endDate);

        // 2. Query Pendapatan (Income) periode aktif dan periode pembanding
        $incomeCurrent = (float) FinancialTransaction::whereBetween('transaction_date', [$period['current_start'], $period['current_end']])
            ->where('transaction_type', 'income')
            ->sum('amount');

        $incomeLast = (float) FinancialTransaction::whereBetween('transaction_date', [$period['prev_start'], $period['prev_end']])
            ->where('transaction_type', 'income')
            ->sum('amount');

        // 3. Query Pengeluaran (Expense) periode aktif dan periode pembanding
        $expenseCurrent = (float) FinancialTransaction::whereBetween('transaction_date', [$period['current_start'], $period['current_end']])
            ->where('transaction_type', 'expense')
            ->sum('amount');

        $expenseLast = (float) FinancialTransaction::whereBetween('transaction_date', [$period['prev_start'], $period['prev_end']])
            ->where('transaction_type', 'expense')
            ->sum('amount');

        // 4. Perhitungan Laba Bersih (Net Profit = Pendapatan - Pengeluaran)
        $netProfitCurrent = $incomeCurrent - $expenseCurrent;
        $netProfitLast = $incomeLast - $expenseLast;

        // 5. Perhitungan Saldo Kas:
        // - Net Balance Periode Ini: Surplus / Defisit bulan ini (Income - Expense)
        // - Saldo Kas Kumulatif: Akumulasi seluruh uang masuk dikurangi uang keluar hingga akhir periode aktif
        $cumulativeIncome = (float) FinancialTransaction::where('transaction_date', '<=', $period['current_end'])
            ->where('transaction_type', 'income')
            ->sum('amount');

        $cumulativeExpense = (float) FinancialTransaction::where('transaction_date', '<=', $period['current_end'])
            ->where('transaction_type', 'expense')
            ->sum('amount');

        $cumulativeBalance = $cumulativeIncome - $cumulativeExpense;

        // Saldo kas kumulatif periode sebelumnya sebagai pembanding
        $cumulativeIncomeLast = (float) FinancialTransaction::where('transaction_date', '<=', $period['prev_end'])
            ->where('transaction_type', 'income')
            ->sum('amount');

        $cumulativeExpenseLast = (float) FinancialTransaction::where('transaction_date', '<=', $period['prev_end'])
            ->where('transaction_type', 'expense')
            ->sum('amount');

        $cumulativeBalanceLast = $cumulativeIncomeLast - $cumulativeExpenseLast;

        // 6. Return struktur data object yang lengkap dan siap dipakai di view dashboard/report
        return (object) [
            'income' => (object) [
                'current_month'     => $incomeCurrent,
                'last_month'        => $incomeLast,
                'growth_percentage' => $this->calculateGrowthPercentage($incomeCurrent, $incomeLast),
            ],
            'expense' => (object) [
                'current_month'     => $expenseCurrent,
                'last_month'        => $expenseLast,
                'growth_percentage' => $this->calculateGrowthPercentage($expenseCurrent, $expenseLast),
            ],
            'net_profit' => (object) [
                'current_month'     => $netProfitCurrent,
                'last_month'        => $netProfitLast,
                'growth_percentage' => $this->calculateGrowthPercentage($netProfitCurrent, $netProfitLast),
            ],
            'balance' => (object) [
                'current_month'          => $netProfitCurrent, // Net cash flow bulan/periode ini
                'cumulative'             => $cumulativeBalance, // Saldo kas kumulatif hingga akhir periode
                'last_month_cumulative'  => $cumulativeBalanceLast,
                'growth_percentage'      => $this->calculateGrowthPercentage($cumulativeBalance, $cumulativeBalanceLast),
            ],
            'period' => (object) $period,
        ];
    }

    /**
     * Query untuk menghitung tren pemasukan vs pengeluaran selama 6 bulan terakhir.
     * Digunakan untuk grafik perbandingan (Pemasukan vs Pengeluaran).
    
     */
    public function getIncomeExpenseTrend6Months($startDate = null, $endDate = null)
    {
        // 1. Tentukan tanggal akhir dan tanggal mulai (mundur 5 bulan ke belakang = total 6 bulan)
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = Carbon::parse($startDate)->startOfMonth()->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfMonth()->endOfDay();
            
        } else {
            $endDateRef = !empty($endDate) ? $this->extractEndDate($endDate) : Carbon::now();
            $startDate = (clone $endDateRef)->subMonths(5)->startOfMonth()->startOfDay();
            $endDate = (clone $endDateRef)->endOfMonth()->endOfDay();
          
        }
        
        // 2. Agregasi data bulanan per transaction_type langsung dari database
        $rawTransactions = FinancialTransaction::select(
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as `year_month`'),
            'transaction_type',
            DB::raw('SUM(amount) as total')
        )
            ->whereBetween('transaction_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('year_month', 'transaction_type')
            ->get()
            ->groupBy('year_month');

        // 3. Looping persis 6 bulan menggunakan CarbonPeriod agar bulan tanpa transaksi tetap tercatat 0
        $period = CarbonPeriod::create($startDate, '1 month', $endDate);
        $trendData = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $monthName = $date->translatedFormat('F Y');

            $monthlyGroup = $rawTransactions->get($key);
            $income = (float) (optional($monthlyGroup?->firstWhere('transaction_type', 'income'))->total ?? 0);
            $expense = (float) (optional($monthlyGroup?->firstWhere('transaction_type', 'expense'))->total ?? 0);
            $profit = $income - $expense;

            $trendData[] = [
                'year_month' => $key,
                'month'      => $monthName,
                'income'     => $income,
                'expense'    => $expense,
                'net_profit' => $profit,
            ];
        }

        return $trendData;
    }

    /**
     * Query untuk menghitung arus kas setiap bulannya selama 12 bulan terakhir.
     * Digunakan untuk visualisasi grafik arus kas bulanan (Cash Inflow, Cash Outflow, Net Cash Flow).
     *
     */
    public function getCashFlowMonthly12Months($startDate = null, $endDate = null)
    {
        // 1. Tentukan tanggal akhir dan tanggal mulai (mundur 11 bulan ke belakang = total 12 bulan)
        $endDateRef = !empty($endDate) ? $this->extractEndDate($endDate) : Carbon::now();
        $startDate = (clone $endDateRef)->subMonths(11)->startOfMonth()->startOfDay();
        $endDate = (clone $endDateRef)->endOfMonth()->endOfDay();
        // 2. Agregasi data transaksi bulanan langsung dari financial_transactions
        $rawTransactions = FinancialTransaction::select(
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as `year_month`'),
            'transaction_type',
            DB::raw('SUM(amount) as total')
        )
            ->whereBetween('transaction_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('year_month', 'transaction_type')
            ->get()
            ->groupBy('year_month');

        // 3. Looping persis 12 bulan menggunakan CarbonPeriod
        $period = CarbonPeriod::create($startDate, '1 month', $endDate);
        $cashFlowData = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $monthName = $date->translatedFormat('F Y');

            $monthlyGroup = $rawTransactions->get($key);
            $cashIn = (float) (optional($monthlyGroup?->firstWhere('transaction_type', 'income'))->total ?? 0);
            $cashOut = (float) (optional($monthlyGroup?->firstWhere('transaction_type', 'expense'))->total ?? 0);
            $netCashFlow = $cashIn - $cashOut;

            $cashFlowData[] = [
                'year_month'    => $key,
                'month'         => $monthName,
                'cash_in'       => $cashIn,
                'cash_out'      => $cashOut,
                'net_cash_flow' => $netCashFlow,
                'status'        => $netCashFlow > 0 ? 'Surplus' : ($netCashFlow < 0 ? 'Defisit' : 'Imbang'),
            ];
        }

        return $cashFlowData;
    }

    /**
     * Helper privat untuk menentukan tanggal periode aktif & pembanding secara periodik
     */
    protected function resolvePeriodDates($dateOrStartDate = null, $endDate = null)
    {
        Carbon::setLocale('id');

        if (!empty($dateOrStartDate) && str_contains($dateOrStartDate, ' s/d ')) {
            // Kasus 1: Input berupa string rentang daterangepicker ("2026-11-01 s/d 2026-11-30")
            $parts = explode(' s/d ', $dateOrStartDate);
            $currentStart = Carbon::parse(trim($parts[0]))->startOfDay();
            $currentEnd = Carbon::parse(trim($parts[1]))->endOfDay();

            // Hitung durasi hari untuk periode pembanding
            $diffDays = $currentStart->diffInDays($currentEnd) + 1;
            $prevStart = (clone $currentStart)->subDays($diffDays)->startOfDay();
            $prevEnd = (clone $currentStart)->subDay()->endOfDay();

            $label = $currentStart->translatedFormat('d M Y') . ' - ' . $currentEnd->translatedFormat('d M Y');
            $prevLabel = $prevStart->translatedFormat('d M Y') . ' - ' . $prevEnd->translatedFormat('d M Y');
        } elseif (!empty($dateOrStartDate) && !empty($endDate)) {
            // Kasus 2: Input berupa parameter terpisah ($startDate, $endDate)
            $currentStart = Carbon::parse($dateOrStartDate)->startOfDay();
            $currentEnd = Carbon::parse($endDate)->endOfDay();

            $diffDays = $currentStart->diffInDays($currentEnd) + 1;
            $prevStart = (clone $currentStart)->subDays($diffDays)->startOfDay();
            $prevEnd = (clone $currentStart)->subDay()->endOfDay();

            $label = $currentStart->translatedFormat('d M Y') . ' - ' . $currentEnd->translatedFormat('d M Y');
            $prevLabel = $prevStart->translatedFormat('d M Y') . ' - ' . $prevEnd->translatedFormat('d M Y');
        } elseif (!empty($dateOrStartDate)) {
            // Kasus 3: Input berupa 1 tanggal spesifik ("2026-11-15" atau "2026-11") -> ambil 1 bulan penuh
            $refDate = Carbon::parse($dateOrStartDate);
            $currentStart = (clone $refDate)->startOfMonth()->startOfDay();
            $currentEnd = (clone $refDate)->endOfMonth()->endOfDay();

            $prevStart = (clone $refDate)->subMonth()->startOfMonth()->startOfDay();
            $prevEnd = (clone $refDate)->subMonth()->endOfMonth()->endOfDay();

            $label = $currentStart->translatedFormat('F Y');
            $prevLabel = $prevStart->translatedFormat('F Y');
        } else {
            // Kasus 4: Default bulan berjalan saat ini
            $now = Carbon::now();
            $currentStart = (clone $now)->startOfMonth()->startOfDay();
            $currentEnd = (clone $now)->endOfMonth()->endOfDay();

            $prevStart = (clone $now)->subMonth()->startOfMonth()->startOfDay();
            $prevEnd = (clone $now)->subMonth()->endOfMonth()->endOfDay();

            $label = $currentStart->translatedFormat('F Y');
            $prevLabel = $prevStart->translatedFormat('F Y');
        }

        return [
            'current_start' => $currentStart->format('Y-m-d'),
            'current_end'   => $currentEnd->format('Y-m-d'),
            'prev_start'    => $prevStart->format('Y-m-d'),
            'prev_end'      => $prevEnd->format('Y-m-d'),
            'label'         => $label,
            'prev_label'    => $prevLabel,
        ];
    }

    /**
     * Helper privat untuk mengekstrak tanggal akhir dari berbagai format input
     */
    protected function extractEndDate($dateOrEndDate)
    {
        if (str_contains($dateOrEndDate, ' s/d ')) {
            $parts = explode(' s/d ', $dateOrEndDate);
            return Carbon::parse(trim($parts[1]));
        }
        return Carbon::parse($dateOrEndDate);
    }

    /**
     * Helper privat untuk menghitung persentase pertumbuhan (+ / -)
     */
    protected function calculateGrowthPercentage($current, $last)
    {
        if ($last == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $last) / abs($last)) * 100, 2);
    }
}
