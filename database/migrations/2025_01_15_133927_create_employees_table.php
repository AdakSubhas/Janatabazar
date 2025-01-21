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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['admin','employee'])->default('employee');
            $table->string('name',255)->nullable();
            $table->string('username',255)->unique()->nullable();
            $table->string('password',255)->nullable();
            $table->string('mobile',255)->unique()->nullable();
            $table->string('email',255)->unique()->nullable();
            $table->longText('address',255)->nullable();
            $table->string('profile',255)->nullable();
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
        Schema::dropIfExists('employees');
    }
};
