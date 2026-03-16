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
        Schema::create('practical_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained('syllabus')
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->constrained('syllabus_units')
                ->cascadeOnDelete();

            $table->text('lab_learning_outcome');

            $table->text('exercise');

            $table->unsignedTinyInteger('hours');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
            $table->unique(['syllabus_id','order_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practical_tasks');
    }
};
