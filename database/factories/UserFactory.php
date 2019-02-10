<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
//        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
   return [
       'slug' => str_slug($title),
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
       'channel_id' => function() {
           return factory('App\Channel')->create()->id;
       },
       'title' => $faker->sentence,
       'body' => $faker->paragraph,
       'replies_count' => 0,
       'visits_count' => 0,
       'locked' => false
   ];
});

$factory->define(App\Channel::class, function (Faker $faker) {
    $name = $faker->name;
    return [
        'name' => $name,
        'slug' => str_slug($name)
    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function() {
            return factory('App\Thread')->create()->id;
        },
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => \Illuminate\Support\Str::uuid()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});

/**
 * Run a factory through tinker console
 *
 * php artisan tinker
 * $threads = factory('App\Thread', 50)->create();
 * $threads->each(function($thread) { factory('App\Reply', 10)->create(['thread_id' => $thread->id]); });
 *
 */
