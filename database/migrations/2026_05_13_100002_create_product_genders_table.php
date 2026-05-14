<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_genders', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('gender_id')
                ->constrained('genders')
                ->cascadeOnDelete();

            $table->primary([
                'product_id',
                'gender_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_genders');
    }
};
