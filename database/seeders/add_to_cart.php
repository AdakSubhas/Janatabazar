<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class add_to_cart extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('add_to_cart')->insert([
            [
                'order_id'      => NULL,
                'daily_price_id'=> 1,
                'product_id'    => 1,
                'customer_id'   => 1,
                'quantity'      => 2,
                'status'        => 0,
                'created_at'    => carbon::now(),
            ],
            [
                'order_id'      => NULL,
                'daily_price_id'=> 2,
                'product_id'    => 2,
                'customer_id'   => 1,
                'quantity'      => 3,
                'status'        => 0,
                'created_at'    => carbon::now(),
            ],
            [
                'order_id'      => NULL,
                'daily_price_id'=> 3,
                'product_id'    => 3,
                'customer_id'   => 1,
                'quantity'      => 4,
                'status'        => 0,
                'created_at'    => carbon::now(),
            ],
        ]);
    }
}
