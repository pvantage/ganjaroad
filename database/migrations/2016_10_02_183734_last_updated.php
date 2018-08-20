<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LastUpdated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classifieds', function ($table) {
			$table->timestamp('last_updated')->after('updated_at');
		});
		
		Schema::table('nearme', function ($table) {
			$table->timestamp('last_updated')->after('updated_at');
		});
		
		Schema::table('ads', function ($table) {
			$table->timestamp('last_updated')->after('updated_at');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classifieds DROP last_updated');
        DB::statement('ALTER TABLE nearme DROP last_updated');
        DB::statement('ALTER TABLE ads DROP last_updated');
    }
}
