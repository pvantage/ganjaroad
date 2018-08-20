<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMulticategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classified_categories_values', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id')->default(0);
			$table->integer('classified_id')->default(0);
		});
		
		DB::statement('ALTER TABLE classifieds DROP category_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classifieds', function ($table) {
			$table->integer('category_id')->after('id');
		});
		
		$table = 'classified_categories_values';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
