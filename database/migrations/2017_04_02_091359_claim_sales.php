<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClaimSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_user', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->default(0);
			$table->integer('admin_id')->default(0);
			$table->tinyInteger('approved')->default(0);
			$table->tinyInteger('reviewed')->default(0);
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
        $table = 'claim_user';
        Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
    }
}
