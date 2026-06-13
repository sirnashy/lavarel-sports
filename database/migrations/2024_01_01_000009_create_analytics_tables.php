<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page_url');
            $table->string('referer')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('stream_views', function (Blueprint $table) {
            $table->id();
            $table->string('match_id')->index();
            $table->string('session_id')->index();
            $table->string('ip_address')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->string('stream_source')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stream_views');
        Schema::dropIfExists('visitors');
    }
};