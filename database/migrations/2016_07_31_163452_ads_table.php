<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_positions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
			$table->integer('published')->default(0);
            $table->timestamps();
            $table->softDeletes();
		});
		
		Schema::create('ads', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('position_id');
			$table->unsignedInteger('user_id');
			$table->string('title');
			$table->string('slug')->nullable();
			$table->string('image')->nullable();
			$table->integer('published')->default(0);
			$table->integer('views')->default(0);
			$table->integer('clicks')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = 'ads_positions';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('ads_positions');
		
		$table = 'ads';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('ads');
    }
}
