<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', static function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->commonFields(); // Using the commonFields macro DRY principle
            $table->softDeletes(); // Soft deletes for the table
            $table->bigInteger('IDEs')->autoIncrement()->first(); // Primary key with auto-increment
            $table->index(['email', 'phone']); // Indexes for faster lookups
            //            $table->charset = 'utf8mb4';
            //            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
