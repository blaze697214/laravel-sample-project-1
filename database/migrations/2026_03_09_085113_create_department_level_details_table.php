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
        Schema::create('department_level_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('levels')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->tinyInteger('courses_offered');
            $table->tinyInteger('compulsory_to_complete');
            $table->tinyInteger('elective_to_complete');
            $table->tinyInteger('th_hrs');
            $table->tinyInteger('tu_hrs');
            $table->tinyInteger('pr_hrs');
            $table->smallInteger('credits');
            $table->smallInteger('marks');
            $table->boolean('is_configured');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_level_details');
    }
};
