<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('old_id')->nullable();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('code')->nullable();

            $table->string('gtin')->nullable();
            $table->string('mpn')->nullable();

            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('base_price', 10, 2)->nullable();

            $table->boolean('active')->default(true);

            $table->boolean('popular')->default(false);
            $table->boolean('is_new')->default(false);

            $table->boolean('to_order')->default(false);
            $table->boolean('is_absent')->default(false);

            $table->integer('rating')->nullable();

            $table->unsignedInteger('review_count')->default(0);

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index('category_id');
            $table->index('active');
            $table->index('popular');
            $table->index('is_new');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
