<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('payment_id');
			$table->unsignedInteger('plan_id');
			$table->unsignedInteger('item_id');
			$table->unsignedInteger('qty');
			$table->string('type');
			$table->float('price');
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
		$table = 'payment_items';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
