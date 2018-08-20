<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdsWh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_positions', function ($table) {
			$table->integer('width')->default(0)->after('slug');
			$table->integer('height')->default(0)->after('slug');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE ads_positions DROP width');
        DB::statement('ALTER TABLE ads_positions DROP height');
    }
}
