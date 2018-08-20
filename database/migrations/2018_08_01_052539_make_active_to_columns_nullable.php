<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeActiveToColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE classifieds MODIFY active_to timestamp NULL');
        DB::statement('ALTER TABLE nearme MODIFY active_to timestamp NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classifieds MODIFY active_to timestamp NOT NULL');
        DB::statement('ALTER TABLE nearme MODIFY active_to timestamp NOT NULL');
    }
}
