<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            customers::class,
            product_categories::class,
            products::class,
            add_to_cart::class,
            customers_address::class,
            daily_price_list::class,
            EmployeeSeeder::class,
        ]);
    }
}
