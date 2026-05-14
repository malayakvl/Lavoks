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
        Schema::table('genders', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('active');
            $table->string('slug')->nullable()->after('order');
            $table->string('emoji')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('genders', function (Blueprint $table) {
            $table->dropColumn(['order', 'slug', 'emoji']);
        });
    }
};
