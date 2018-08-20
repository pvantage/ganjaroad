<?php

namespace App\Console\Commands;

use App\Cart;
use App\Plans;
use App\Classified;
use Illuminate\Console\Command;

/**
 * Class FixRecurringPayments.
 *
 * @package App\Console\Commands
 */
class FixRecurringPayments extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix-recurring-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix recurring payments problems with renewal costs.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $countClassifieds = 0;
        $classifieds = Classified::where('renewal_cost', '>', 0)
            ->where('paid', '=', 1)
            ->where('active', '=', 1)
            ->where('published', '=', 1)
            ->where('recurring', '=', 1)
            ->get()
        ;

        foreach ($classifieds as $classified) {
            if (!$classified->activeByUser()) {
                continue;
            }

            $price = Plans::getItemPrice('classifieds', $classified);
            $qty = Cart::getAddToCartQty('classifieds', $classified);
            $correctRenewalCost = $price * $qty;

            if ($correctRenewalCost <= $classified->renewal_cost) {
                continue;
            }

            $classified->renewal_cost = $correctRenewalCost;
            $classified->save();

            $countClassifieds++;
        }

        $this->info(sprintf('Fixed classifieds: %d', $countClassifieds));
    }
}
