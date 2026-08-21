<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronActivity;

class NotifyCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-cron-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi otomatis secara berkala';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $count = CronActivity::orderBy('id', 'DESC')->get();
            if (count($count) < 0) {
                CronActivity::create(['cron_name'=>'Notify Cron']);
            }else{
                CronActivity::where('id', $count[0]->id)->update(['cron_name'=>'Notify Cron']);
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://sukalelang.id/notification/cron.php');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);
            \Log::info('Cron job executed successfully! : '. count($count));
        } catch (\Throwable $th) {
            \Log::info('Cron job executed failed! : '. $th);
        }
        return Command::SUCCESS;
    }
}
