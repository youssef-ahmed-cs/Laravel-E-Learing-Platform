<?php

use App\Enums\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tests')) {
            Schema::create('tests', static function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('content');
                $table->string('status')->default(User::ADMIN->value);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        // Drop table if it exists (handles both table and potential view conflicts in sqlite)
        Schema::dropIfExists('tests');
    }
};
