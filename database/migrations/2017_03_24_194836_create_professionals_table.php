<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->integer('gender_id')->unsigned();
            $table->string('password')->nullable();
            $table->string('tel', 25);
            $table->char('address', 100);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('appointments', function($table) {
           $table->foreign('professional_id')
              ->references('id')->on('professionals')
              ->onUpdate('cascade')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professionals');
    }
}
