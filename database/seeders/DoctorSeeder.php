<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('users')->truncate();
        DB::table('doctors')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rita_id = DB::table('users')->insertGetId([
            'name' => 'Dr. Rita',
            'email' => 'rita123@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
        ]);

        $smith_id = DB::table('users')->insertGetId([
            'name' => 'Dr. Smith',
            'email' => 'smith123@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
        ]);

        $jatka_id = DB::table('users')->insertGetId([
            'name' => 'Dr. jatka',
            'email' => 'jatka123@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
        ]);

        DB::table('doctors')->insert([
            ['name' => 'Dr. Rita', 'email' => 'rita123@gmail.com', 'user_id' => $rita_id],
            ['name' => 'Dr. Smith', 'email' => 'smith123@gmail.com', 'user_id' => $smith_id],
            ['name' => 'Dr. Jatka', 'email' => 'jatka123@gmail.com', 'user_id' => $jatka_id],
        ]);
    }
}
