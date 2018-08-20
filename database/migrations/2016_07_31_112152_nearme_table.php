<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NearmeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nearme_categories', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
			$table->string('slug')->nullable();
			$table->integer('priority')->default(0);
			$table->integer('published')->default(0);
            $table->timestamps();
            $table->softDeletes();
		});
		
		Schema::create('nearme', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('category_id');
			$table->unsignedInteger('user_id');
			$table->string('title');
			$table->string('slug')->nullable();
			$table->text('content');
			$table->integer('published')->default(0);
			$table->integer('views')->default(0);
			$table->string('lattitude', 255);
			$table->string('longitude', 255);
			$table->string('address1', 255);
			$table->string('address2', 255);
			$table->string('city', 255);
			$table->string('state', 255);
			$table->string('zip', 255);
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
        $table = 'nearme_categories';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('nearme_categories');
		
		$table = 'nearme';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('nearme');
    }
}
