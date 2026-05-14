<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();  // women, unisex, men
            $table->boolean('active')->default(true);
            $table->integer('order')->default(0);
            $table->string('slug')->nullable();
            $table->string('emoji')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genders');
    }
};
