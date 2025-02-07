<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_access', function (Blueprint $table) {
            $table->id();
            $table->string('user_table')->comment('customer,delivery,store');
            $table->integer('user_id');
            $table->string('device_id')->comment('Device Id');
            $table->string('device_name')->comment('Device Name');
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
        Schema::dropIfExists('device_access');
    }
};
