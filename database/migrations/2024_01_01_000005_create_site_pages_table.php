<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('show_in_nav')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_pages');
    }
};