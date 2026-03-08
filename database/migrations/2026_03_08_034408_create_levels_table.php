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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_year_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->tinyInteger('order_no');
            $table->boolean('is_audit');
            $table->timestamps();
            $table->unique(['curriculum_year_id', 'order_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
