<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAdCouponTable.
 */
class CreateAdCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_coupon', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id');
            $table->string('ad_type');
            $table->integer('coupon_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_coupon');
    }
}
