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
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('username',255)->unique()->nullable();
            $table->string('password',255);
            $table->string('email',255)->unique()->nullable();
            $table->string('mobile',255)->unique()->nullable();
            $table->longText('address',255);
            $table->string('deliver_area',255)->nullable();
            $table->string('zipcode',255)->nullable();
            $table->tinyInteger('active_status')->comment('1="Online",0="Offline"')->default(0);
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
        Schema::dropIfExists('delivery_partners');
    }
};
