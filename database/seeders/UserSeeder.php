<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = 'Administrator';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('admin');
        $admin->save();

        $user = new User();
        $user->name = 'Vikar Maulana Arrisyad';
        $user->email = 'user@example.com';
        $user->password = Hash::make('user');
        $user->save();

        $user->assignRole('karyawan');
        $admin->assignRole('admin');
    }
}
