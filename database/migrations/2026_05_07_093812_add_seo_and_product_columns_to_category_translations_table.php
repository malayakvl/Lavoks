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
        Schema::table('category_translations', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_content')->nullable();
            $table->string('product_title')->nullable();
            $table->text('product_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_content', 'product_title', 'product_description']);
        });
    }
};
