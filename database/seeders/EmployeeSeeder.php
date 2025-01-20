<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'name'      => 'Admin',
                'username'  => 'admin@gmail.com',
                'password'  => Hash::make('12345'),
                'type'     => 'employee',
                'mobile'    => '8918249127',
                'email'     => 'admin@gmail.com',
                'address'   => 'Jhargram,West Bengal,PIN-721507',
                'profile'      => '2025-01-15-16-34-0490546493051b2e4dabird.jpg',
                'created_at'     =>  carbon::now(),
                'updated_at'     =>  carbon::now(),
                'status'    => 1,
            ],
        ]);
    }
}
