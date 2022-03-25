<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'test01admin.aipxperts@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$A/FI6UtgRrNL2gvq5s4nOejRdOFgA.ttIc0u9iZIy97ZMxozFY0zO', // aipX@1234
            'remember_token' => Str::random(10),
        ]);

    }
}
