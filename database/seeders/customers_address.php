<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class customers_address extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customer_address')->insert([
            [
                'customer_id'   => 1,
                'address'       => 'Jhargram,West Bengal,PIN-721507',
                'city'          => 'Jhargram',
                'state'         => 'West Bengal',
                'zipcode'       => '721507',
                'status'        => 1,
                'created_at'    => carbon::now(),
            ],
        ]);
    }
}
