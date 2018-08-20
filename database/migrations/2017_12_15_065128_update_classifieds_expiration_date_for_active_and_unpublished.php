<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClassifiedsExpirationDateForActiveAndUnpublished extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE `classifieds` SET `last_updated` = \'2017-12-15 00:00:00\', `active_to` = \'2018-02-28 00:00:00\', `published` = 1, `active` = 1, `paid` = 1 WHERE `active_to` < \'2018-02-28 00:00:00\'');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
