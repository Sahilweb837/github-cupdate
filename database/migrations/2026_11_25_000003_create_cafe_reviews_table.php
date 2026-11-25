<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cafe_reviews')) {
            Schema::create('cafe_reviews', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('date_place_id')->nullable()->index();
                $table->string('cafe_name', 140);
                $table->string('city', 100)->index();
                $table->unsignedTinyInteger('rating')->default(5);
                $table->string('safety_tag', 60)->default('Women-Friendly & Safe');
                $table->text('review_text');
                $table->boolean('is_approved')->default(true);
                $table->boolean('is_flagged')->default(false);
                $table->unsignedInteger('shares_count')->default(0);
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        }

        if (! Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->integer('sender_id')->index();
                $table->integer('receiver_id')->index();
                $table->text('message');
                $table->string('image_path')->nullable();
                $table->boolean('is_read')->default(false);
                $table->boolean('is_flagged')->default(false);
                $table->boolean('deleted_by_sender')->default(false);
                $table->boolean('deleted_by_receiver')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        }

        if (! Schema::hasTable('chat_requests')) {
            Schema::create('chat_requests', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('sender_id')->index();
                $table->string('status', 30)->default('pending');
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        }

        if (! Schema::hasTable('video_calls')) {
            Schema::create('video_calls', function (Blueprint $table) {
                $table->id();
                $table->integer('caller_id')->index();
                $table->integer('receiver_id')->index();
                $table->string('channel_name', 100);
                $table->string('status', 30)->default('calling');
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->unsignedInteger('duration_seconds')->default(0);
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('from_user_id')->nullable();
                $table->string('type', 50);
                $table->string('title', 150);
                $table->text('content');
                $table->boolean('is_read')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        }
    }

    public function down(): void
    {
        // Preserve user reviews
    }
};
