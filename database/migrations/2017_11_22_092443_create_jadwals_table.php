<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('jadwals', function (Blueprint $table) {
          $table->increments('id');
          $table->string('hari');
          $table->string('jam');
          $table->integer('dokter')->unsigned()->nullable();
          $table->timestamps();
      });
      Schema::table('jadwals', function($table){
        $table->foreign('dokter')->references('id')->on('dokters')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::dropIfExists('jadwals');
    }
}
