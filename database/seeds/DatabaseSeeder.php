<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Patient::class, 50)->create();
        factory(App\Professional::class, 5)->create();
        
        DB::table('specialties')->insert(['specialty' => 'Masajes']);
        DB::table('specialties')->insert(['specialty' => 'Estética']);
        DB::table('specialties')->insert(['specialty' => 'Pedicuría']);
        DB::table('specialties')->insert(['specialty' => 'Manicuría']);
        DB::table('specialties')->insert(['specialty' => 'Depilación']);
        DB::table('specialties')->insert(['specialty' => 'Rehabilitación deportiva']);

        DB::table('treatments')->insert(['treatment' => 'Drenaje Linfático']);
        DB::table('treatments')->insert(['treatment' => 'Uñas arqueadas']);
        DB::table('treatments')->insert(['treatment' => 'Ultrasonido']);
        DB::table('treatments')->insert(['treatment' => 'Reflexología']);
        DB::table('treatments')->insert(['treatment' => 'Limpieza de acné']);
        DB::table('treatments')->insert(['treatment' => 'Masaje con piedras calientes']);

        DB::table('series')->insert(['series' => '2 x 1', 'cant' => 2]);
        DB::table('series')->insert(['series' => '10 sesiones al precio de 9', 'cant' => 10]);
        DB::table('series')->insert(['series' => '5 sesiones en una semana', 'cant' => 5]);
        DB::table('series')->insert(['series' => '1 sesión por mes 2017', 'cant' => 12]);

        factory(App\Appointment::class, 50)->create();
    }
}
