<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carousel_items', function (Blueprint $table) {
            $table->id();
            $table->string('slidable_type'); // 'category' или 'product'
            $table->unsignedBigInteger('slidable_id'); // ID категории или продукта
            $table->integer('position')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['slidable_type', 'slidable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_items');
    }
};
