<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TierPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tier_prices', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('category_id')->default(0);
			$table->string('type');
			$table->integer('from')->default(0);
			$table->integer('to')->default(0);
			$table->integer('priority')->default(0);
			$table->float('price')->default(0);
            $table->timestamps();
		});
		
		Schema::create('classified_multicategory', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('classified_id')->default(0);
			$table->integer('category_id')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = 'tier_prices';
        Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
		
		$table = 'classified_multicategory';
        Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
