<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workout_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_id')->constrained();
            $table->foreignId('exercise_id')->constrained();
            $table->integer('set_number');
            $table->integer('reps')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->enum('side', ['left', 'right', 'both'])->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->boolean('is_drop_set')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_sets');
    }
};
