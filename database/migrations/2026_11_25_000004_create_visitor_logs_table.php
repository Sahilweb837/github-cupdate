<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 100)->index();
                $table->integer('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->string('country', 80)->nullable()->index();
                $table->string('region', 100)->nullable();
                $table->string('city', 100)->nullable()->index();
                $table->string('path', 255)->default('/')->index();
                $table->string('referrer', 255)->nullable();
                $table->string('device_type', 20)->default('Desktop');
                $table->timestamp('last_seen_at')->nullable()->index();
                $table->timestamp('created_at')->nullable()->useCurrent()->index();
            });
        }

        if (! Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->integer('reporter_id')->index();
                $table->integer('reported_id')->index();
                $table->string('reason', 255);
                $table->text('content_snapshot')->nullable();
                $table->string('status', 30)->default('pending');
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        }
    }

    public function down(): void
    {
        // Preserve visitor analytics
    }
};
