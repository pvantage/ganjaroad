<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Coupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code')->unique();
			$table->integer('author')->default(0);
			$table->integer('uses_per_coupon')->default(0);
			$table->integer('type')->default(0);
			$table->float('discount')->default(0);
			$table->dateTime('published_from')->nullable();
			$table->dateTime('published_to')->nullable();
            $table->timestamps();
			$table->softDeletes();
		});
		
		Schema::table('payments', function ($table) {
			$table->string('coupon')->after('amount');
			$table->float('discount')->after('coupon');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = 'coupons';
        Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
		
		DB::statement('ALTER TABLE payments DROP coupon');
        DB::statement('ALTER TABLE payments DROP discount');
    }
}
