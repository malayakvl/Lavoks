<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type'); // percent | fixed

            $table->decimal('value', 10, 2);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index('product_id');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_discounts');
    }
};
