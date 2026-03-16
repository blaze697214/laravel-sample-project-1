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
        Schema::create('programme_outcomes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            $table->foreignId('curriculum_year_id')
                ->constrained('curriculum_years')
                ->cascadeOnDelete();

            $table->string('code');

            $table->enum('type', ['PO', 'PSO']);

            $table->text('description');

            $table->unsignedTinyInteger('order');

            $table->timestamps();
            $table->unique(['programme_id','curriculum_year_id','code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_outcomes');
    }
};
