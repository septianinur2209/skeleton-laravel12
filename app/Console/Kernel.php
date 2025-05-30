<?php

namespace App\Console;

use App\Jobs\AutoApproveExpiredTokens;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Mendefinisikan jadwal perintah (command) untuk aplikasi.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }

    /**
     * Mendaftarkan perintah-perintah untuk aplikasi.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands'); // Memuat perintah dari folder 'Commands'

        require base_path('routes/console.php'); // Memuat rute untuk perintah-perintah
    }
}
