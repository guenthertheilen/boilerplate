<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Permission::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
