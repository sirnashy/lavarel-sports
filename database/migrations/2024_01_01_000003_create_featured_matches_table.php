<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_matches', function (Blueprint $table) {
            $table->id();
            $table->string('match_id')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('match_starts_at')->nullable()->index();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_matches');
    }
};