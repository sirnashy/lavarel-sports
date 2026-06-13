<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
            $table->boolean('is_active')->default(true)->after('is_admin');
            $table->string('avatar')->nullable()->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('avatar');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'is_active', 'avatar', 'last_login_at', 'last_login_ip']);
        });
    }
};