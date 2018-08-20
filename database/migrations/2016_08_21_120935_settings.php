<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
			$table->string('code');
			$table->text('value');
			$table->string('type');
			$table->text('options');
			$table->string('placeholder');
			$table->string('rules');
			$table->integer('position')->default(0);
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
        $table = 'settings';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
