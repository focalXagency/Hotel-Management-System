<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reservation;
use App\Events\EndingSoonEvent;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->call(function () {
            $now = Carbon::now();
            $endIn24Hours = $now->copy()->addDay();

            $reservationsEndingIn24Hours = Reservation::where('end_date', '>=', $now)
                ->where('end_date', '<=', $endIn24Hours)
                ->with('room', 'user')
                ->get();

            foreach ($reservationsEndingIn24Hours as $reservation) {
                event(new EndingSoonEvent($reservation));
            }
        })->daily()->at('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
