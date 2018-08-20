<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

use App\Classified as AppClassified;
use App\Helpers\Template;

class ClassifiedPriority extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classified_priority';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move classifieds to top of category';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = Carbon::now();
        $move_classified = Template::getSetting('move_classified_to_top');
        $days = $move_classified->diffInDays($now);
        $date_compare = $now->addDays($days);
        $classifieds = AppClassified::getActive()->where('last_updated', '<', $date_compare);
        if($classifieds->count()) {
            foreach($classifieds->get() as $classified) {
               $classified->last_updated = $today;
               $classified->save();
            }
        }
	}
}
