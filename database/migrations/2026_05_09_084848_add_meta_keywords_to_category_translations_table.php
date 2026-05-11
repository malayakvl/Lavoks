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
            $table->text('meta_keywords')->nullable()->after('meta_title');
            $table->text('product_meta_title')->nullable()->after('meta_keywords');
            $table->text('product_meta_description')->nullable()->after('product_meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn(['meta_keywords', 'product_meta_title', 'product_meta_description']);
        });
    }
};
