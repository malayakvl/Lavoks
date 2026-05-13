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
        Schema::create('color_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('color_id')
                ->constrained('colors')
                ->cascadeOnDelete();

            $table->string('locale');

            $table->string('title');
            $table->string('slug');

            $table->text('description')->nullable();

            $table->unique(['color_id', 'locale']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_translations');
    }
};
