<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_leathers', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('leather_id')
                ->constrained('leathers')
                ->cascadeOnDelete();

            $table->primary([
                'product_id',
                'leather_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_leathers');
    }
};
