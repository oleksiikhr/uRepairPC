<?php declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @inheritDoc
     */
    protected $commands = [
        //
    ];

    /**
     * @inheritDoc
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * @inheritDoc
     */
    protected function commands(): void
    {
        parent::commands();

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
