<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenewalCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classifieds', function ($table) {
			$table->float('renewal_cost')->before('created_at');
			$table->timestamp('active_to')->after('last_updated');
		});
		
		Schema::table('nearme', function ($table) {
			$table->float('renewal_cost')->before('created_at');
			$table->timestamp('active_to')->after('last_updated');
		});
		
		Schema::table('ads', function ($table) {
			$table->float('renewal_cost')->before('created_at');
			$table->timestamp('active_to')->after('last_updated');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classifieds DROP renewal_cost');
        DB::statement('ALTER TABLE classifieds DROP active_to');
		
		DB::statement('ALTER TABLE nearme DROP renewal_cost');
        DB::statement('ALTER TABLE nearme DROP active_to');
		
		DB::statement('ALTER TABLE ads DROP renewal_cost');
        DB::statement('ALTER TABLE ads DROP active_to');
    }
}
