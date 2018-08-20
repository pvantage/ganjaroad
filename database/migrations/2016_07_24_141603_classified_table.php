<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassifiedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classified_categories', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
			$table->string('slug')->nullable();
			$table->integer('parent')->default(0);
            $table->timestamps();
            $table->softDeletes();
		});
		
		Schema::create('classified_fields', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
			$table->string('code');
			$table->string('type');
			$table->integer('position')->default(0);
			$table->integer('published')->default(0);
            $table->timestamps();
            $table->softDeletes();
		});
		
		Schema::create('classified_fields_values', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('classified_id')->default(0);
			$table->text('value');
            $table->timestamps();
		});

		Schema::create('classifieds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('category_id');
			$table->unsignedInteger('user_id');
			$table->string('title');
			$table->string('slug')->nullable();
			$table->text('content');
			$table->integer('views')->default(0);
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
        //take backup before dropping table
		$table = 'classifieds';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('classifieds');
		
		$table = 'classified_fields_values';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('classified_fields_values');
		
		$table = 'classified_fields';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('classified_fields');

		$table = 'classified_categories';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop('classified_categories');
    }
}
