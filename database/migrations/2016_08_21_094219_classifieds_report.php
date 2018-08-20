<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedsReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->integer('item_id')->default(0);
			$table->integer('user_id')->default(0);
			$table->string('user_ip');
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
        $table = 'reported_items';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
