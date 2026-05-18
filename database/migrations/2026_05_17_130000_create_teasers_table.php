<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teasers', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->text('caption')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('active')->default(true);
            $table->string('youtube_code')->nullable();
            $table->string('page_url')->nullable();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teasers');
    }
};
