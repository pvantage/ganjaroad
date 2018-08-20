<?php

namespace App\Console\Commands;

use App\Payments;
use Carbon\Carbon;
use App\Ads as AppAds;
use Illuminate\Console\Command;

/**
 * Class Ads.
 *
 * @package App\Console\Commands
 */
class Ads extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ads cron';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $records = AppAds::getActive(false)->where('published_to', '<', Carbon::now())->get();

        if (!$records->count()) {
            return true;
        }

        foreach ($records as $object) {
            if ($object->recurring) {
                $result = Payments::doRecurring($object);

                if (!$result) {
                    $object->unPaid();
                }
            } else {
                $object->unPaid();
            }
        }
    }
}
