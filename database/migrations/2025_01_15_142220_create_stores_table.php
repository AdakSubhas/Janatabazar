<?php

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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('store_name',255);
            $table->string('username',255)->unique();
            $table->string('password');
            $table->string('mobile',255)->unique();
            $table->string('email',255)->unique();
            $table->LongText('address');
            $table->string('zip_code',255);
            $table->string('image')->nullable();
            $table->tinyInteger('active_stats')->comment('1="Online",0="Offline"')->default(0);
            $table->tinyInteger('status')->comment('1="Active",0="Inactive"')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
