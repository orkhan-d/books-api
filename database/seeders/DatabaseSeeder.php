<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->create([
            'login'=>'admin',
            'password'=>'admin',
            'admin'=>true
        ]);

        User::query()->create([
            'login'=>'guest1',
            'password'=>'guest1',
            'admin'=>false
        ]);

        User::query()->create([
            'login'=>'guest2',
            'password'=>'guest2',
            'admin'=>false
        ]);
    }
}
