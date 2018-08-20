<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedsMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classifieds', function ($table) {
			$table->string('map_address')->after('content');
			$table->string('lattitude')->after('map_address');
			$table->string('longitude')->after('lattitude');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classifieds DROP map_address');
        DB::statement('ALTER TABLE classifieds DROP lattitude');
        DB::statement('ALTER TABLE classifieds DROP longitude');
    }
}
