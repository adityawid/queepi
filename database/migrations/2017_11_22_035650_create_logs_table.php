<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('logs', function (Blueprint $table) {
          $table->increments('id');
          $table->date('log');//from qrcode
          $table->integer('check_ups')->unsigned()->nullable();
          $table->timestamps();
      });
      Schema::table('logs', function($table){
        $table->foreign('check_ups')->references('id')->on('check_ups')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
