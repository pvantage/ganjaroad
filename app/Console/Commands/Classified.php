<?php

namespace App\Console\Commands;

use App\Payments;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Classified as AppClassified;

/**
 * Class Classified.
 *
 * @package App\Console\Commands
 */
class Classified extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'classified cron';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $records = AppClassified::getActive(false, false)->where('active_to', '<', Carbon::now())->get();

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
