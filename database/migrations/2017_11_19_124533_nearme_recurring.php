<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmeRecurring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearme', function ($table) {
			$table->integer('recurring_period')->default(0)->after('renewal_cost');
			$table->string('recurring_period_type')->after('recurring_period');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE nearme DROP recurring_period');
        DB::statement('ALTER TABLE nearme DROP recurring_period_type');
    }
}
