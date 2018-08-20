<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedCategoriesLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classified_categories', function ($table) {
			$table->tinyInteger('home')->after('slug');
			$table->string('lattitude')->default(0)->after('home');
			$table->string('longitude')->default(0)->after('lattitude');
			$table->string('address')->after('home');
		});
		
		DB::statement('ALTER TABLE classified_categories DROP state');
        DB::statement('ALTER TABLE classified_categories DROP country');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classified_categories DROP home');
        DB::statement('ALTER TABLE classified_categories DROP lattitude');
        DB::statement('ALTER TABLE classified_categories DROP longitude');
        DB::statement('ALTER TABLE classified_categories DROP address');
		
		Schema::table('classified_categories', function ($table) {
			$table->string('state')->after('slug');
			$table->string('country')->after('slug');
		});
    }
}
