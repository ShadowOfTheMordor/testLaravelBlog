<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::factory(4)->create();
        \App\Models\Post::factory(15)->create();
        //добавим потом админов
        $administrators=config("blog.administrators");
        foreach($administrators as $admin)
        {
//            dd($admin);
            User::create([
                "name" => $admin["name"],
                "email" => $admin["email"],
                "password" => Hash::make($admin["password"]),
                "is_admin" => 1,
                "email_verified_at" => now(),
                "remember_token" => Str::random(10)
            ]);
        }
    }
}
