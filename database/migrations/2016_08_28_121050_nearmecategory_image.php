<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmecategoryImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearme_categories', function ($table) {
			$table->string('image')->after('slug');
			$table->int('position')->after('image');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE nearme_categories DROP image');
        DB::statement('ALTER TABLE nearme_categories DROP position');
    }
}
