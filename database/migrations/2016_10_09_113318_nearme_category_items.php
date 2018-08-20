<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmeCategoryItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nearme_items_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('slug')->nullable();
			$table->integer('position')->default(0);
			$table->integer('published')->default(0);
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
        $table = 'nearme_items_categories';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
