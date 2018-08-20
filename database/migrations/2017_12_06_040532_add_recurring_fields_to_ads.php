<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecurringFieldsToAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->integer('recurring_period')->default(0)->after('renewal_cost');
            $table->string('recurring_period_type')->after('recurring_period');
            $table->integer('recurring_discount')->default(0)->after('recurring_period_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            DB::statement('ALTER TABLE ads DROP recurring_period');
            DB::statement('ALTER TABLE ads DROP recurring_period_type');
            DB::statement('ALTER TABLE ads DROP recurring_discount');
        });
    }
}
