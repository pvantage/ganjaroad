<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('nearme', function ($table) {
			$table->tinyInteger('atm')->after('url');
			$table->string('min_age')->after('atm');
			$table->tinyInteger('wheelchair')->after('min_age');
			$table->tinyInteger('security')->after('wheelchair');
			$table->tinyInteger('credit_cards')->after('security');
			$table->text('hours')->after('credit_cards');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE nearme DROP atm');
        DB::statement('ALTER TABLE nearme DROP min_age');
        DB::statement('ALTER TABLE nearme DROP wheelchair');
        DB::statement('ALTER TABLE nearme DROP security');
        DB::statement('ALTER TABLE nearme DROP credit_cards');
        DB::statement('ALTER TABLE nearme DROP hours');
    }
}
