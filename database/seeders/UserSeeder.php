<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'name' => 'Ihsan Maulana',
            'email' => 'sansanmau26@gmail.com',
            'password' => 'admin',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Fachruddin Ghalibi',
            'email' => 'fachry@email.com',
            'password' => 'doctor',
            'role' => 'doctor',
        ]);

        User::create([
            'name' => 'Muhammad Fadlan Kamal',
            'email' => 'fadlan@email.com',
            'password' => 'patient',
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Haikal Alfaro',
            'email' => 'haikal@email.com',
            'password' => 'doctor',
            'role' => 'doctor',
        ]);

        User::create([
            'name' => 'Najwa Aulia Zahra',
            'email' => 'ara@email.com',
            'password' => 'patient',
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Maulana Ihsan',
            'email' => 'ihsan@email.com',
            'password' => 'doctor',
            'role' => 'doctor',
        ]);

        User::create([
            'name' => 'Naufal Ajhar El Hafiz',
            'email' => 'naufal@email.com',
            'password' => 'patient',
            'role' => 'patient',
        ]);
    }
}
