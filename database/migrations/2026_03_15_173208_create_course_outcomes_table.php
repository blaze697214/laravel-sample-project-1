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
        Schema::create('course_outcomes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('co_code');

            $table->text('description');

            $table->unsignedTinyInteger('order');

            $table->timestamps();
            $table->unique(['syllabus_id','co_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_outcomes');
    }
};
