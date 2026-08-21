<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendBroadcastWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-broadcast-winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim broadcast ke pemenang';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://sukalelang.id/notification/broadcast.php');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);
            \Log::info('Cron job 2 executed successfully! ');
        } catch (\Throwable $th) {
            \Log::info('Cron job 2 executed failed! : '. $th);
        }
        return Command::SUCCESS;
    }
}
