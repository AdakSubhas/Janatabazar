<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class daily_price_list extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('daily_price_list')->insert([
            [
                'state_id'      => 36,
                'district_id'   => 637,
                'pin_id'        => 1,
                'category_id'   => 1,
                'product_id'    => 1,
                'price'         => 40.00,
                'status'        => 1,
                'created_at'    => carbon::now(),
            ],
            [
                'state_id'      => 36,
                'district_id'   => 637,
                'pin_id'        => 1,
                'category_id'   => 1,
                'product_id'    => 2,
                'price'         => 00.00,
                'status'        => 1,
                'created_at'    => carbon::now(),
            ],
            [
                'state_id'      => 36,
                'district_id'   => 637,
                'pin_id'        => 1,
                'category_id'   => 1,
                'product_id'    => 3,
                'price'         => 10.00,
                'status'        => 1,
                'created_at'    => carbon::now(),
            ],
            [
                'state_id'      => 36,
                'district_id'   => 637,
                'pin_id'        => 1,
                'category_id'   => 2,
                'product_id'    => 4,
                'price'         => 200.00,
                'status'        => 1,
                'created_at'    => carbon::now(),
            ],
            [
                'state_id'      => 36,
                'district_id'   => 637,
                'pin_id'        => 1,
                'category_id'   => 2,
                'product_id'    => 5,
                'price'         => 220.00,
                'status'        => 1,
                'created_at'    => carbon::now(),
            ],
        ]);
    }
}
