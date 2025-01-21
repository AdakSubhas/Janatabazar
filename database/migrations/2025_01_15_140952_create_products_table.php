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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('serial_number',255)->unique();
            $table->string('item',255)->unique();
            $table->double('price',8,2);
            $table->string('photo',255)->nullable();
            $table->string('units',255);
            $table->string('state',255)->nullable();
            $table->string('district',255)->nullable();
            $table->string('city',255)->nullable();
            $table->integer('min_order')->comment('Quantity');
            $table->integer('max_order')->comment('Quantity');
            $table->LongText('description')->nullable();
            $table->tinyInteger('status')->comment('1="Active",0="Inactive"')->default(1);
            $table->integer('update_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
