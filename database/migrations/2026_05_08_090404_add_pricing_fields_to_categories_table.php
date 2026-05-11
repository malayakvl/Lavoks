<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('emoji')->nullable()->after('image');

            $table->decimal('percent_change', 8, 2)->nullable()->after('emoji');
            $table->decimal('fix_price', 10, 2)->nullable()->after('percent_change');
            $table->decimal('discount', 8, 2)->nullable()->after('fix_price');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'emoji',
                'percent_change',
                'fix_price',
                'discount',
            ]);
        });
    }
};
