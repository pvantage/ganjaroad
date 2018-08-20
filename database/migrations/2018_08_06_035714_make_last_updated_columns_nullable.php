<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeLastUpdatedColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE classifieds MODIFY last_updated timestamp NULL');
        DB::statement('ALTER TABLE nearme MODIFY last_updated timestamp NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE classifieds MODIFY last_updated timestamp NOT NULL');
        DB::statement('ALTER TABLE nearme MODIFY last_updated timestamp NOT NULL');
    }
}
