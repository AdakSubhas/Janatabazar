<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceListController extends Controller
{
    public function DailyPriceList()
    {
        $data = DB::table('daily_price_list')->get();
        // dd($data);
        return view('price-list.index',compact('data'));
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'daily_price_list' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('daily_price_list');
        $filePath = $file->getRealPath();

        // Read CSV file
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle); // Get the header row
            $rows = [];

            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);

            foreach ($rows as $row) {
                [$stateName, $districtName, $pin_code, $categoryName, $productName, $price] = $row;

                // Find IDs based on names
                $stateId = DB::table('states')->where('name', $stateName)->value('id');
                $districtId = DB::table('districts')->where('name', $districtName)->value('id');
                $pincodeId = DB::table('pincodes')->where('pincode', $pin_code)->value('id');
                $categoryId = DB::table('product_categories')->where('category_name', $categoryName)->value('id');
                $productId = DB::table('products')->where('item', $productName)->value('id');

                // Skip if any ID is not found
                if (!$stateId || !$districtId || !$pincodeId || !$categoryId || !$productId) {
                    return redirect()->back()->with('error', 'Some records have invalid names.');
                }

                // Insert or update data
                DB::table('daily_price_list')->updateOrInsert(
                    [
                        'state_id' => $stateId,
                        'district_id' => $districtId,
                        'pin_id' => $pincodeId,
                        'category_id' => $categoryId,
                        'product_id' => $productId,
                    ],
                    ['price' => $price, 'updated_at' => now()]
                );
            }

            return redirect()->back()->with('success', 'CSV file uploaded and processed successfully.');
        }

        return redirect()->back()->with('error', 'Failed to process the uploaded file.');
    }

}
