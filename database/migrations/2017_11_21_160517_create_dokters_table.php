<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('dokters', function (Blueprint $table) {
          $table->increments('id');
          $table->string('nipk');
          $table->string('nama');
          $table->integer('id_poli')->unsigned()->nullable();
          $table->string('email')->nullable();
          $table->string('no_hp')->nullable();
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
        Schema::drop('dokters');
    }
}
