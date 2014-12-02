<?php namespace RAFIE\Quicknote\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateNotesTable extends Migration
{

    public function up()
    {
        Schema::create('rafie_quicknote_notes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rafie_quicknote_notes');
    }

}
