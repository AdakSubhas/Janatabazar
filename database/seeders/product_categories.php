<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class product_categories extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_categories')->insert([
            [
                'category_name' => 'Vegetable',
                'status' => 1,
            ],
            [
                'category_name' => 'Nonveg',
                'status' => 1,
            ],
        ]);
    }
}
