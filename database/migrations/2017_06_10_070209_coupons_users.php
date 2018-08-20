<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CouponsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function ($table) {
			$table->float('max_amount')->default(0)->after('discount');
			$table->integer('uses_per_user')->default(0)->after('uses_per_coupon');
		});
		
		Schema::create('coupons_users', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('coupon_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = 'coupons_users';
        Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
		
		DB::statement('ALTER TABLE coupons DROP uses_per_user');
    }
}
