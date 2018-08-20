<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recurring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('vault', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('vault_id');
            $table->timestamps();
            $table->softDeletes();
		});
		
        Schema::table('payments', function ($table) {
			$table->unsignedInteger('vault_id')->default(0)->after('transaction_id');
			$table->string('tranasction_type')->after('vault_id');
		});
		
		Schema::table('ads', function ($table) {
			$table->tinyInteger('recurring')->default(0)->after('paid');
			$table->unsignedInteger('vault_id')->default(0)->after('recurring');
		});
		
		Schema::table('nearme', function ($table) {
			$table->tinyInteger('recurring')->default(0)->after('paid');
			$table->unsignedInteger('vault_id')->default(0)->after('recurring');
		});
		
		Schema::table('classifieds', function ($table) {
			$table->tinyInteger('recurring')->default(0)->after('paid');
			$table->unsignedInteger('vault_id')->default(0)->after('recurring');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$table = 'vault';
		Storage::disk('local')->put($table.'_'.date('Y-m-d_H-i-s').'.bak', json_encode(DB::table($table)->get()));
		Schema::drop($table);
		
        DB::statement('ALTER TABLE payments DROP vault_id');
        DB::statement('ALTER TABLE payments DROP tranasction_type');
        
		DB::statement('ALTER TABLE ads DROP recurring');
		DB::statement('ALTER TABLE ads DROP vault_id');
		
		DB::statement('ALTER TABLE nearme DROP recurring');
		DB::statement('ALTER TABLE nearme DROP vault_id');
		
		DB::statement('ALTER TABLE classifieds DROP recurring');
		DB::statement('ALTER TABLE classifieds DROP vault_id');
    }
}
