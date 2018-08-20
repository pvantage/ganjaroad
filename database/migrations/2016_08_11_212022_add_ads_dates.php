<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdsDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function ($table) {
			$table->string('url')->after('image');
			$table->dateTime('published_from')->after('url');
			$table->dateTime('published_to')->after('published_from');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE blogs DROP url');
        DB::statement('ALTER TABLE blogs DROP published_from');
        DB::statement('ALTER TABLE blogs DROP published_to');
    }
}
