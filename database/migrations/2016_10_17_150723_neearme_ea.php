<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NeearmeEa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearme', function ($table) {
			$table->text('first_time')->after('phone');
			$table->string('facebook')->after('first_time');
			$table->string('instagram')->after('facebook');
			$table->string('twitter')->after('instagram');
		});
		
		Schema::table('nearme_items', function ($table) {
			$table->string('ea')->after('terpenes');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE nearme DROP first_time');
        DB::statement('ALTER TABLE nearme DROP facebook');
        DB::statement('ALTER TABLE nearme DROP instagram');
        DB::statement('ALTER TABLE nearme DROP twitter');
		
        DB::statement('ALTER TABLE nearme_items DROP ea');
    }
}
