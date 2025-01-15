<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class products extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id'   => 1,
                'item'         => 'Carrots',
                'price'         => 40.00,
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 1,
                'item'         => 'Potatoes',
                'price'         => 20.00,
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 1,
                'item'         => 'Tomatoes',
                'price'         => 10.00,
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 2,
                'item'         => 'Checken',
                'price'         => 200.00,
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 2,
                'item'         => 'Checken Leg(6 Pis)',
                'price'         => 220.00,
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
        ]);
    }
}
