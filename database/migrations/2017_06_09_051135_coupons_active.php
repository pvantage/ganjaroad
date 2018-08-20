<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CouponsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function ($table) {
			$table->tinyInteger('active')->default(0)->after('author');
			$table->integer('times_used')->default(0)->after('active');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE coupons DROP active');
        DB::statement('ALTER TABLE times_used DROP active');
    }
}
