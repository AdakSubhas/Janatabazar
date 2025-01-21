<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class customers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name'      => 'Ishan Adak',
                'mobile'    => '8918249127',
                'email'     => 'ishan.adak987@gmail.com',
                'password'  => Hash::make('12345'),
                'photo'     => '2025-01-15-16-34-0490546493051b2e4dabird.jpg',
                'status'    => 1,
            ],
        ]);
    }
}
