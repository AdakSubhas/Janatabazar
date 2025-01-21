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
        Schema::create('customer_address', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->longText('address');
            $table->string('city',255)->nullable();
            $table->string('state',255)->nullable();
            $table->string('zipcode',255)->nullable();
            $table->tinyInteger('status')->comment('1="Active",0="Inactive"')->default(0);
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
        Schema::dropIfExists('customer_address');
    }
};
