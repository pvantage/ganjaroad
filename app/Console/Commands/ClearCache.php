<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\CacheHelper;

class ClearCache extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearcache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear cache cron';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		CacheHelper::clearAllCache();
    }
}
