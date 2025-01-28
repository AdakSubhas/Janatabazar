<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_price_list', function (Blueprint $table) {
            $table->id();
            $table->integer('state_id');
            $table->integer('district_id');
            $table->integer('pin_id');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->double('purchase_price',8,2)->comment('purchase price')->default(0);
            $table->double('price',8,2)->comment('Selling Price')->default(0);
            $table->tinyInteger('status')->comment('1="Active",0="Inactive"')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_price_list');
    }
};
