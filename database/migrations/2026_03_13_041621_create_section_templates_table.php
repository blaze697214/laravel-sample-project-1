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
        Schema::create('section_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('intro_text')->nullable();
            $table->enum('section_type',['text','list','table','matrix']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_templates');
    }
};
