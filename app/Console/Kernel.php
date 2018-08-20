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
        // Commands\Inspire::class,
        Commands\Classified::class,
        Commands\Nearme::class,
        Commands\Ads::class,
        Commands\Weed::class,
        Commands\Email::class,
        Commands\ClearCache::class,
        Commands\ClassifiedPriority::class,
        Commands\FixRecurringPayments::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('classified')->cron('0 3 * * * *');
        $schedule->command('fix-recurring-payments')->cron('0 4 * * * *');
        $schedule->command('nearme')->cron('0 3 * * * *');
        $schedule->command('ads')->cron('0 3 * * * *');
        $schedule->command('weed')->cron('0 3 * * * *');
        $schedule->command('email')->cron('0 3 * * * *'); //reminder email
        
        $schedule->command('clearcache')->daily();
        $schedule->command('classified_priority')->daily();
    }
}
