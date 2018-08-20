<?php

namespace App\Console\Commands;

use App\Payments;
use Carbon\Carbon;
use App\Nearme as AppNearme;
use Illuminate\Console\Command;

/**
 * Class Nearme.
 *
 * @package App\Console\Commands
 */
class Nearme extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nearme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'nearme cron';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $records = AppNearme::getActive(false)->where('active_to', '<', Carbon::now())->get();

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
