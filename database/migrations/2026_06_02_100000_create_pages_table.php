<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->string('menu_name')->nullable();
            $table->string('page_header')->nullable();
            $table->text('page_content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('top_menu')->nullable();
            $table->string('slug_trans')->nullable();

            $table->index('slug');
            $table->index('active');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
