<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatesToBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function ($table) {
			$table->integer('published')->default(0)->after('views');
			$table->dateTime('published_from')->nullable()->after('published');
			$table->dateTime('published_to')->nullable()->after('published_from');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE blogs DROP published');
        DB::statement('ALTER TABLE blogs DROP published_from');
        DB::statement('ALTER TABLE blogs DROP published_to');
    }
}
