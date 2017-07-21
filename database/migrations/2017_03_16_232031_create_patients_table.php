<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {            
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('cellphone', 25);
            $table->string('tel', 25)->nullable();
            $table->integer('dni');
            $table->tinyInteger('civil_status')->nullable()->unsigned();
            $table->char('gender', 4);
            $table->char('address', 100);
            $table->date('birthdate')->nullable();
            $table->string('area', 255)->nullable();
            $table->text('facebook')->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('patients');
    }
}
