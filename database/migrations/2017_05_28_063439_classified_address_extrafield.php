<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedAddressExtrafield extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classified_fields', function ($table) {
			$table->string('field_type')->after('rules');
		});
		
		Schema::table('classifieds', function ($table) {
			$table->tinyInteger('hide_map')->after('map_address');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classified_fields DROP field_type');
        DB::statement('ALTER TABLE classifieds DROP hide_map');
    }
}
