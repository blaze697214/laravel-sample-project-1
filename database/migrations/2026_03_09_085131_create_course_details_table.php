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
        Schema::create('course_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_course_id')->constrained('department_course')->cascadeOnDelete();
            $table->string('course_code');
            $table->tinyInteger('th_hrs');
            $table->tinyInteger('tu_hrs');
            $table->tinyInteger('pr_hrs');
            $table->smallInteger('credits');
            $table->tinyInteger('th_paper_hrs');
            $table->tinyInteger('th_marks');
            $table->tinyInteger('test_marks');
            $table->tinyInteger('pr_marks');
            $table->tinyInteger('or_marks');
            $table->tinyInteger('tw_marks');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_configured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_details');
    }
};
