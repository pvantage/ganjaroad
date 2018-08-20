<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Images extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('item_id');
			$table->string('type');
			$table->string('image');
            $table->timestamps();
            $table->softDeletes();
		});
		
		Schema::drop('files');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = 'images';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
