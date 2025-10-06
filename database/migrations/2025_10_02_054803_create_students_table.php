<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', static function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->commonFields(); # Using the commonFields macro DRY principle
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
