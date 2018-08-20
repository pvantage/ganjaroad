<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_categories', function ($table) {
			$table->text('meta_description')->after('slug');
			$table->text('meta_keywords')->after('meta_description');
		});
		
		Schema::table('blogs', function ($table) {
			$table->text('meta_description')->after('content');
			$table->text('meta_keywords')->after('meta_description');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE blog_categories DROP meta_description');
        DB::statement('ALTER TABLE blog_categories DROP meta_keywords');
		
        DB::statement('ALTER TABLE blogs DROP meta_description');
        DB::statement('ALTER TABLE blogs DROP meta_keywords');
    }
}
