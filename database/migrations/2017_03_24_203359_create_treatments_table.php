<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('specialty_id')->unsigned()->nullable();
            $table->string('treatment', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('appointments', function($table) {
           $table->foreign('treatment_id')
              ->references('id')->on('treatments')
              ->onUpdate('cascade')
              ->onDelete('cascade');
        });

        Schema::table('treatments', function($table) {
           $table->foreign('specialty_id')
              ->references('id')->on('specialties')
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
        Schema::dropIfExists('treatments');
    }
}
