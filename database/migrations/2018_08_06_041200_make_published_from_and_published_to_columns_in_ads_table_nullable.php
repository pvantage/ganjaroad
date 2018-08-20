<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePublishedFromAndPublishedToColumnsInAdsTableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE ads MODIFY published_from timestamp NULL');
        DB::statement('ALTER TABLE ads MODIFY published_to timestamp NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE ads MODIFY published_from timestamp NOT NULL');
        DB::statement('ALTER TABLE ads MODIFY published_to timestamp NOT NULL');
    }
}
