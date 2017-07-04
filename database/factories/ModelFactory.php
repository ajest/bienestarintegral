<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Patient::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'tel' => $faker->phoneNumber,
        'gender' => strlen($faker->name) > 10 ? 'H' : 'M',
        'address' => $faker->address,
        'created_at' => $faker->dateTimeBetween($startDate = '-180 days', $endDate = '+180 days')
    ];
});

$factory->define(App\Appointment::class, function (Faker\Generator $faker) {
    return [
        'title' => rtrim($faker->sentence(3), '.'),
        'professional_id' => $faker->numberBetween(1, 5),
        'patient_id' => $faker->numberBetween(1, 50),
        'specialty_id' => $faker->numberBetween(1, 6),
        'treatment_id' => $faker->numberBetween(1, 6),
        'series_id' => $faker->numberBetween(1, 4),
        'date' => $faker->numberBetween(2015, 2018) . '-' . $faker->numberBetween(1, 12) . '-' . $faker->numberBetween(1, 28),
        'hour' => $faker->numberBetween(07, 24) . ':00',
        'comments' => rtrim($faker->sentence(10), '.'),
        'created_at' => $faker->dateTimeBetween($startDate = '-180 days', $endDate = '+180 days')
    ];
});

$factory->define(App\Professional::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'tel' => $faker->phoneNumber,
        'gender' => strlen($faker->name) > 10 ? 'H' : 'M',
        'address' => $faker->address,
        'created_at' => $faker->dateTimeBetween($startDate = '-180 days', $endDate = '0 days'),
        'updated_at' => $faker->dateTimeBetween($startDate = '+1 days', $endDate = '+180 days')
    ];
});