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
        Schema::create('unit_topics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_unit_id')
                ->constrained('syllabus_units')
                ->cascadeOnDelete();

            $table->enum('type', [
                'learning_outcome',
                'topic',
            ]);

            $table->text('content');

            $table->unsignedTinyInteger('order');

            $table->timestamps();
            $table->unique(['syllabus_unit_id','type','order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_topics');
    }
};
