<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');
            $table->string('target')->default('_self');
            $table->string('icon')->nullable();
            $table->string('location')->default('header'); // header, footer, sidebar
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('navigation_menus')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_menus');
    }
};