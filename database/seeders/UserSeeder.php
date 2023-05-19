<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'usama',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'type' => 'ADMIN',
        ]);
        DB::table('users')->insert([
            'name' => 'Noroz',
            'email' => 'noroz@admin.com',
            'password' => Hash::make('12345678'),
            'type' => 'USER',
        ]);
    }
}
