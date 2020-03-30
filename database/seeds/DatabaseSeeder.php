<?php

use App\User;
use Illuminate\Database\Seeder;
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
        $superadmin = User::create([
            'nip' => '123456789123456789',
            'username' => 'inisuperadmin',
            'nama' => 'Ini Super Admin',
            'password' => Hash::make('12345678'),
            'role' => 'superadmin'
        ]);

        $admin = User::create([
            'nip' => '987654321987654321',
            'username' => 'iniadmin',
            'nama' => 'Ini Admin',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        $pimpinan = User::create([
            'nip' => '123456123456789789',
            'username' => 'inipimpinan',
            'nama' => 'Ini Pimpinan',
            'password' => Hash::make('12345678'),
            'role' => 'pimpinan'
        ]);
    }
}
