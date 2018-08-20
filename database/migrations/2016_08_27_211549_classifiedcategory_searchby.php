<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedcategorySearchby extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classified_categories', function ($table) {
			$table->string('state')->after('slug');
			$table->string('country')->after('slug');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classified_categories DROP state');
        DB::statement('ALTER TABLE classified_categories DROP country');
    }
}
