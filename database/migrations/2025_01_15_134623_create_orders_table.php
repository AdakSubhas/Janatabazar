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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id',255);
            $table->integer('cusromer_id');
            $table->string('address_id',255)->comment('Customer Address');
            $table->integer('store_id');
            $table->integer('delivery_id');
            $table->tinyInteger('status')->default(0)->nullable();
            $table->integer('otp1')->comment('for store & delivey')->nullable();
            $table->integer('otp2')->comment('for customer & delivery')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->double('discount', 8, 2)->nullable();
            $table->double('total', 8, 2)->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_by',255)->comment('username')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
