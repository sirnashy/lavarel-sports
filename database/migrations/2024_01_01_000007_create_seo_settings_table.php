<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique()->index(); // home, match, search, sport, etc.
            $table->string('meta_title_template');
            $table->text('meta_description_template');
            $table->string('og_image')->nullable();
            $table->string('twitter_card')->default('summary_large_image');
            $table->json('extra_meta')->nullable();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, boolean, json, image
            $table->string('group')->default('general');
            $table->string('label');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('seo_settings');
    }
};