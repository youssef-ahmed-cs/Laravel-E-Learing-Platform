<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->text('content')->nullable();
            $table->dateTime('dateline');
            $table->boolean('completed')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained()
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('lesson_id')->constrained('lessons')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
