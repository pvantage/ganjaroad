<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_renew_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('renewal_period_name');
            $table->integer('renewal_time')->default(1);
            $table->enum('renewal_time_period', ['d', 'w', 'm', 'y'])->default('d');
            $table->enum('renewal_type', ['nearme', 'classified', 'banner_ad']);
            $table->integer('renewal_discount');
            $table->timestamps();
        });

        DB::statement('INSERT INTO `ad_renew_options` VALUES (1,\'Every 2 Weeks\',2,\'w\',\'classified\',0,\'2017-12-15 06:16:05\',\'2017-12-15 06:16:26\'),(2,\'Every 6 Months\',6,\'m\',\'classified\',10,\'2017-12-15 06:16:43\',\'2017-12-15 06:16:43\'),(3,\'Every 12 Months\',12,\'m\',\'classified\',15,\'2017-12-15 06:16:59\',\'2017-12-15 06:16:59\'),(4,\'Every 12 Months\',1,\'y\',\'nearme\',0,\'2017-12-15 06:17:14\',\'2017-12-15 06:17:14\'),(5,\'Every 12 Months\',1,\'y\',\'banner_ad\',0,\'2017-12-15 06:17:24\',\'2017-12-15 06:17:24\');');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('setting_discounts');
    }
}
