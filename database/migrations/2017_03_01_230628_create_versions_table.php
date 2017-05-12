<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
        	$table->charset = 'utf8';
        	$table->collation = 'utf8_bin';
        	$table->engine = 'InnoDB';
        	$table->increments('id');
        	$table->text('title');
        	$table->text('body');
        	$table->text('translated_title');
        	$table->text('translated_body');
        	$table->binary('image');
        	$table->text('url');
        	$table->integer('updated_by');
        	$table->timestamp('updated_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versions');
    }
}
