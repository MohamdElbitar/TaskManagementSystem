<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'testManager@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $manager->assignRole('manager');

        $user = User::create([
            'name' => 'User',
            'email' => 'testUser@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $user->assignRole('user');
    }
}
