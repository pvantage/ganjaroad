<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentItemsPaid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_items', function ($table) {
			$table->float('discount')->default(0)->after('price');
			$table->float('paid')->default(0)->after('discount');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE payment_items DROP discount');
        DB::statement('ALTER TABLE payment_items DROP paid');
    }
}
