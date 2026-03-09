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
        Schema::create('class_award_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->foreignId('curriculum_year_id')->constrained('curriculum_years')->cascadeOnDelete();
            $table->tinyInteger('total_class_award_courses');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_award_configurations');
    }
};
