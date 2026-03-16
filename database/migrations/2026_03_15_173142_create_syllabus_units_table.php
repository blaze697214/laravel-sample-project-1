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
        Schema::create('syllabus_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('unit_no');

            $table->text('title');

            $table->unsignedTinyInteger('hours');

            $table->unsignedTinyInteger('order');

            $table->timestamps();
            $table->unique(['syllabus_id','unit_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_units');
    }
};
