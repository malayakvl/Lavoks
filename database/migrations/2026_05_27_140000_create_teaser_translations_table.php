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
        Schema::create('teaser_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaser_id')
                ->constrained('teasers')
                ->cascadeOnDelete();
            $table->string('locale')->index();
            $table->text('promo_text')->nullable();
            $table->timestamps();

            $table->unique(['teaser_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaser_translations');
    }
};
