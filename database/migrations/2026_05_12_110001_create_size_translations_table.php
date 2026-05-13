<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('size_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_id')
                ->constrained('sizes')
                ->cascadeOnDelete();
            
            $table->string('locale');
            $table->string('title');              // Название (напр. "Маленький", "Большой")
            $table->text('description')->nullable();
            
            $table->unique(['size_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('size_translations');
    }
};
