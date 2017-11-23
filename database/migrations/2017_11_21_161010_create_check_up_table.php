<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('check_ups', function (Blueprint $table) {
          $table->increments('id');
          $table->date('tgl_check_up');
          $table->integer('pasien')->unsigned()->nullable();
          $table->integer('dokter')->unsigned()->nullable();
          $table->integer('jadwal')->unsigned()->nullable();
          $table->integer('no_antrian');
          $table->timestamps();
      });
      Schema::table('check_ups', function($table){
        $table->foreign('pasien')->references('id')->on('users')->onDelete('cascade');
      });
      Schema::table('check_ups', function($table){
        $table->foreign('dokter')->references('id')->on('dokters')->onDelete('cascade');
      });
      Schema::table('check_ups', function($table){
        $table->foreign('jadwal')->references('id')->on('jadwals')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_ups');
    }
}
