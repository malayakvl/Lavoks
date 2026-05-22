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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('new_model')->default(false)->after('active');
            $table->boolean('new_leather')->default(false)->after('new_model');
            $table->boolean('new_leather_color')->default(false)->after('new_leather');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['new_model', 'new_leather', 'new_leather_color']);
        });
    }
};
