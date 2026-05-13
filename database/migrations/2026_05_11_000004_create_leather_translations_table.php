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
        Schema::create('leather_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('leather_id')
                ->constrained('leathers')
                ->cascadeOnDelete();

            $table->string('locale');

            $table->string('title');
            $table->string('slug');

            $table->text('description')->nullable();

            $table->unique(['leather_id', 'locale']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leather_translations');
    }
};
