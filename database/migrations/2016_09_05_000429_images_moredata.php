<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesMoredata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function ($table) {
			$table->integer('size')->default(0)->after('image');
			$table->string('imagetype')->after('image');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE images DROP size');
        DB::statement('ALTER TABLE images DROP imagetype');
    }
}
