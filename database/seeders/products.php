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
                'serial_number' => 'janata0001',
                'item'          => 'Carrots',
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 1,
                'serial_number' => 'janata0002',
                'item'          => 'Potatoes',
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 1,
                'serial_number' => 'janata0003',
                'item'          => 'Tomatoes',
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 2,
                'serial_number' => 'janata0004',
                'item'          => 'Checken',
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
            [
                'category_id'   => 2,
                'serial_number' => 'janata0005',
                'item'          => 'Checken Leg(6 Pis)',
                'units'         => 'kilogram',
                'min_order'     => '1',
                'max_order'     => '10',
                'status'        => 1,
            ],
        ]);
    }
}
