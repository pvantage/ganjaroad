<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function ($table) {
			$table->integer('paid')->default(0)->after('published');
		});
		
		Schema::table('classifieds', function ($table) {
			$table->integer('paid')->default(0)->after('published');
		});
		
		Schema::table('nearme', function ($table) {
			$table->integer('paid')->default(0)->after('published');
		});
		
		Schema::table('payments', function ($table) {
			$table->string('transaction_id')->after('amount');
			$table->integer('paid')->default(0)->after('transaction_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE ads DROP paid');
        DB::statement('ALTER TABLE classifieds DROP paid');
        DB::statement('ALTER TABLE nearme DROP paid');
		
        DB::statement('ALTER TABLE payments DROP transaction_id');
        DB::statement('ALTER TABLE payments DROP paid');
    }
}
