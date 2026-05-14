<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gender_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gender_id')
                ->constrained('genders')
                ->cascadeOnDelete();
            
            $table->string('locale');
            $table->string('title');  // Жіночі, Унісекс, Чоловічі
            
            $table->unique(['gender_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gender_translations');
    }
};
