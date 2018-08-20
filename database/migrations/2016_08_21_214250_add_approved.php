<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('ads', function ($table) {
			$table->integer('approved')->default(0)->after('published');
		});
		
		Schema::table('nearme', function ($table) {
			$table->integer('approved')->default(0)->after('published');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE ads DROP approved');
        DB::statement('ALTER TABLE nearme DROP approved');
    }
}
