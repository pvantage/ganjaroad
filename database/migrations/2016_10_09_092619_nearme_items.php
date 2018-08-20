<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmeItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nearme_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('nearme');
			$table->unsignedInteger('category_id');
			$table->string('image')->nullable();
			$table->string('name');
			$table->string('thc');
			$table->string('cbd');
			$table->string('cbn');
			$table->string('terpenes');
			$table->string('type_1g');
			$table->string('type_2g');
			$table->string('type_18');
			$table->string('type_14');
			$table->string('type_12');
			$table->string('type_oz');
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
        $table = 'nearme_items';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
