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
        Schema::table('teasers', function (Blueprint $table) {
            $table->string('carousel_type')->default('image')->after('active'); // image, product, category
            $table->json('product_ids')->nullable()->after('carousel_type'); // array of product IDs
            $table->json('category_ids')->nullable()->after('carousel_type'); // array of category IDs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teasers', function (Blueprint $table) {
            //
        });
    }
};
