<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Katip; // Katip modelini buraya import et (eğer modelin adı farklıysa değiştir)
use Carbon\Carbon;

class UpdateKatipActiveStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'katip:update-active-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update is_active to 0 if last_active_at is older than 1 hour';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 1 saatten eski kayıtları bul ve güncelle
        Katip::where('last_active_at', '<', Carbon::now()->subHour())
            ->update(['is_active' => 0]);

        $this->info('Katip active statuses updated successfully!');
    }
}