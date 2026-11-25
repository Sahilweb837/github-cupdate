<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 120)->unique();
                $table->string('state', 100)->default('Himachal Pradesh');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('interest_categories')) {
            Schema::create('interest_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 80);
                $table->string('slug', 100)->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('coffee_dates')) {
            Schema::create('coffee_dates', function (Blueprint $table) {
                $table->id();
                $table->integer('requester_id')->index();
                $table->integer('partner_id')->index();
                $table->string('city', 120);
                $table->string('venue_suggestion', 160)->nullable();
                $table->string('preferred_date', 120);
                $table->string('meeting_preferences', 255)->nullable();
                $table->string('status', 20)->default('pending'); // pending, accepted, rejected, cancelled
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('webrtc_signals')) {
            Schema::create('webrtc_signals', function (Blueprint $table) {
                $table->id();
                $table->integer('video_call_id')->index();
                $table->integer('sender_id')->index();
                $table->string('type', 20); // offer, answer, ice
                $table->longText('payload');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('user_blocks')) {
            Schema::create('user_blocks', function (Blueprint $table) {
                $table->id();
                $table->integer('blocker_id');
                $table->integer('blocked_id');
                $table->timestamps();
                $table->unique(['blocker_id', 'blocked_id']);
            });
        }

        if (! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->string('name', 100);
                $table->string('email', 150);
                $table->string('subject', 140);
                $table->text('message');
                $table->string('status', 20)->default('open'); // open, in_progress, resolved
                $table->text('admin_reply')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('blog_posts')) {
            Schema::create('blog_posts', function (Blueprint $table) {
                $table->id();
                $table->string('title', 180);
                $table->string('slug', 200)->unique();
                $table->text('excerpt');
                $table->longText('body');
                $table->string('cover_image')->nullable();
                $table->integer('author_id')->nullable();
                $table->string('meta_title', 180)->nullable();
                $table->string('meta_description', 255)->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->longText('value')->nullable();
                $table->string('group', 40)->default('platform');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (! Schema::hasColumn('messages', 'image_path')) {
                    $table->string('image_path')->nullable()->after('message');
                }
                if (! Schema::hasColumn('messages', 'is_flagged')) {
                    $table->boolean('is_flagged')->default(false)->after('is_read');
                }
                if (! Schema::hasColumn('messages', 'deleted_by_sender')) {
                    $table->boolean('deleted_by_sender')->default(false);
                }
                if (! Schema::hasColumn('messages', 'deleted_by_receiver')) {
                    $table->boolean('deleted_by_receiver')->default(false);
                }
            });
        }

        if (Schema::hasTable('video_calls')) {
            Schema::table('video_calls', function (Blueprint $table) {
                if (! Schema::hasColumn('video_calls', 'started_at')) {
                    $table->timestamp('started_at')->nullable();
                }
                if (! Schema::hasColumn('video_calls', 'ended_at')) {
                    $table->timestamp('ended_at')->nullable();
                }
                if (! Schema::hasColumn('video_calls', 'duration_seconds')) {
                    $table->unsignedInteger('duration_seconds')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        // Additive migration rollback skipped to protect existing data
    }
};
