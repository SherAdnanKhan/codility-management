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
$factory->define(App\User::class, function () {
    return [
        'name' => 'Admin',
        'email' => 'admin@codiliy.co',
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\TimeTable::class, function () {
    return [
        'end_time' => \Carbon\Carbon::today()->timestamp,
        'start_time' => \Carbon\Carbon::today()->timestamp
    ];
});

