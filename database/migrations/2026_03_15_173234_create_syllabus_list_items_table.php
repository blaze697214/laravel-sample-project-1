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
        Schema::create('syllabus_list_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'objective',
                'student_activity',
                'instructional_strategy',
            ]);

            $table->text('content');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_list_items');
    }
};
