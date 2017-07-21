<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->integer('professional_id')->unsigned()->nullable();
            $table->integer('patient_id')->unsigned();
            $table->integer('specialty_id')->unsigned()->nullable();
            $table->integer('treatment_id')->unsigned()->nullable();
            $table->integer('series_id')->unsigned()->nullable();
            $table->string('date');
            $table->string('hour');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')
                  ->references('id')->on('patients')
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
        Schema::dropIfExists('appointments');
    }
}
