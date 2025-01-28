<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SaveDailyPriceToHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:daily-price-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save daily_price_list data to product_history table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Fetch data from daily_price_list
            $data = DB::table('daily_price_list')->get();
            // status, price, product_id, category_id, pin_id, district_id, state_id
            
            // Insert data into product_history
            foreach ($data as $row) {

                $serialNo = DB::table('products')->where('id',$row->product_id)->value('serial_number');
                $units = DB::table('products')->where('id',$row->product_id)->value('units');

                DB::table('product_history')->insert([
                    'state' => $row->state_id,
                    'district' => $row->district_id,
                    'city' =>'kolkata',
                    'serial_number' =>$serialNo,
                    'price' =>$row->price,
                    'units' =>$units,
                    'price_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->info('Daily price data successfully saved to product history.');
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }

        return 0;
    }
}
