<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmeEmailphone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearme', function ($table) {
			$table->string('email')->after('url');
			$table->string('phone')->after('email');
			$table->tinyInteger('other_address')->default(0)->after('phone');
			$table->text('full_address')->after('other_address');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE nearme DROP email');
        DB::statement('ALTER TABLE nearme DROP phone');
    }
}
