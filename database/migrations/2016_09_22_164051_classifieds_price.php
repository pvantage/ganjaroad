<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedsPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classified_categories', function ($table) {
			$table->float('amount')->after('published');
		});
		
		Schema::table('classified_schema', function ($table) {
			$table->float('amount')->after('position');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classified_categories DROP amount');
        DB::statement('ALTER TABLE classified_schema DROP amount');
    }
}
