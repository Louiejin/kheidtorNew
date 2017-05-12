<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
        	$table->charset = 'utf8';
        	$table->collation = 'utf8_bin';
        	$table->engine = 'InnoDB';
        	$table->increments('id');
        	$table->text('title');
        	$table->text('body');
        	$table->text('image');
        	$table->text('caption');
        	$table->text('url');
        	$table->text('category');
        	$table->text('translated_title');
        	$table->text('translated_body');
        	$table->text('status'); // draft, converted, published
        	$table->integer('published_by')->default(0);
        	$table->timestamp('published_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        	$table->integer('updated_by');
        	$table->timestamp('updated_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        	$table->integer('created_by');
        	$table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
