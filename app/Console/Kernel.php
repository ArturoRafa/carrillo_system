<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\EnviarRecordatoioHoraAntes::class
       /* Commands\EnviarRecordatorioSieteDiasViaje::class,
        Commands\EnviarRecordatorioTresDiasViaje::class,
        Commands\EnviarRecordatorioUnDiaViaje::class,
        Commands\EnviarOfertas::class,*/
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
       /* $schedule->command('recordatorio:horainicio')
            ->everyMinute();*/
       /* $schedule->command('recordatorio:tres')
            ->dailyAt('01:00');
        $schedule->command('recordatorio:uno')
            ->dailyAt('01:30');*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
