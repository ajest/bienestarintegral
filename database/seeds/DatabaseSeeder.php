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
        DB::table('specialties')->insert(['specialty' => 'Masajes', 'description' => 'El masaje es una forma de manipulación de las capas superficiales y profundas de los músculos del cuerpo utilizando varias técnicas, para mejorar sus funciones, ayudar en procesos de curación, disminuir la actividad refleja de los músculos, inhibir la excitabilidad motoneuronal, promover la relajación y el bienestar y como actividad recreativa.']);
        DB::table('specialties')->insert(['specialty' => 'Estética', 'description' => 'El tratamiento estético ayuda a que el cuerpo y en especial la piel refleje un bienestar y salud interior del cuerpo']);
        DB::table('specialties')->insert(['specialty' => 'Pedicuría', 'description' => 'Tratamiento específico enfocado en los pies, su salud superficial y su belleza y estética']);
        DB::table('specialties')->insert(['specialty' => 'Manicuría', 'description' => 'Tratamiento de belleza cosmético para las uñas y manos que suele realizarse en casa o en un salón de belleza. En una manicura se cortan o liman los bordes de las uñas, se realizan masajes a las manos y se aplica esmalte de uñas']);
        DB::table('specialties')->insert(['specialty' => 'Depilación', 'description' => 'La depilación o extirpación del pelo es una técnica cosmética que consiste en eliminar el vello de alguna zona del cuerpo, utilizada particularmente por el ser humano.']);
        DB::table('specialties')->insert(['specialty' => 'Rehabilitación', 'description' => 'El conjunto de medidas sociales, educativas y profesionales destinadas a restituir al paciente minusválido la mayor capacidad e independencia posibles']);

        DB::table('treatments')->insert(['treatment' => 'Drenaje Linfático', 'specialty_id' => 1, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Uñas arqueadas', 'specialty_id' => 2, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Ultrasonido', 'specialty_id' => 1, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Reflexología', 'specialty_id' => 1, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Limpieza de acné', 'specialty_id' => 2, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Masaje con piedras calientes', 'specialty_id' => 1, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Francesitas', 'specialty_id' => 2, 'description' => 'Lorem ipsum']);
        DB::table('treatments')->insert(['treatment' => 'Acupuntura', 'specialty_id' => 1, 'description' => 'Lorem ipsum']);

        DB::table('series')->insert(['series' => '2 x 1', 'cant' => 2]);
        DB::table('series')->insert(['series' => '10 sesiones al precio de 9', 'cant' => 10]);
        DB::table('series')->insert(['series' => '5 sesiones en una semana', 'cant' => 5]);
        DB::table('series')->insert(['series' => '1 sesión por mes 2017', 'cant' => 12]);

        DB::table('questions')->insert(['question' => 'Motivo de la consulta', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Ocupación', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Síntomas', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Ocio', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Presentes desde', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Comenzó por', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Tiene síntomas intermitentes?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Tiene síntomas constantes?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Peor cuándo', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Mejor cuándo', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Se ha hecho masajes antes?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Tratamientos previos', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Sufre o ha sufrido alguna vez problemas cardíacos?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Sufre o ha sufrido alguna vez problemas endocrinos?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Sufre o ha sufrido alguna vez problemas de circulación?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Sufre o ha sufrido alguna vez problemas de tensión arterial?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Sufre o ha sufrido alguna vez problemas de alergia?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => '¿Sufre o ha sufrido alguna vez problemas retención de líquidos?', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Cirugías recientes', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Accidentes', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Prótesis', 'specialty_id' => 1]);
        DB::table('questions')->insert(['question' => 'Medicación', 'specialty_id' => 1]);

        DB::table('genders')->insert(['gender' => 'Hombre']);
        DB::table('genders')->insert(['gender' => 'Mujer']);

        DB::table('civil_statuses')->insert(['civil_status' => 'Soltero']);
        DB::table('civil_statuses')->insert(['civil_status' => 'Casado']);
        
        factory(App\Professional::class, 5)->create();
        factory(App\Patient::class, 50)->create();
        factory(App\Appointment::class, 50)->create();

        DB::table('professionals')->insert([
            'name' => 'Pablo Fumarola',
            'email' => 'pablo.fumarola@gmail.com',
            'password' => bcrypt('123456'),
            'tel' => '+54 11 6890 8443',
            'gender_id' => 1,
            'address' => 'Posta de Pardo 123',
            'created_at' => '2017-08-07',
            'updated_at' => '2017-08-08'
        ]);
    }
}
