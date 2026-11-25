<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('video_queue')) {
            Schema::create('video_queue', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable()->index();
                $table->string('guest_name', 100)->nullable();
                $table->string('peer_id', 255);
                $table->enum('gender', ['male', 'female', 'nonbinary', 'other'])->default('other');
                $table->string('target_gender', 50)->default('everyone');
                $table->string('country', 100)->default('all');
                $table->enum('status', ['waiting', 'matched'])->default('waiting');
                $table->unsignedBigInteger('matched_with_id')->nullable()->index();
                $table->unsignedBigInteger('call_id')->nullable()->index();
                $table->timestamp('last_ping_at')->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        } else {
            Schema::table('video_queue', function (Blueprint $table) {
                if (! Schema::hasColumn('video_queue', 'matched_with_id')) {
                    $table->unsignedBigInteger('matched_with_id')->nullable()->index();
                }
                if (! Schema::hasColumn('video_queue', 'call_id')) {
                    $table->unsignedBigInteger('call_id')->nullable()->index();
                }
                if (! Schema::hasColumn('video_queue', 'last_ping_at')) {
                    $table->timestamp('last_ping_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->text('content');
                $table->string('image_url', 255)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('mood', 60)->nullable();
                $table->boolean('is_approved')->default(true);
                $table->boolean('is_flagged')->default(false);
                $table->unsignedInteger('shares_count')->default(0);
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        } else {
            Schema::table('posts', function (Blueprint $table) {
                if (! Schema::hasColumn('posts', 'city')) {
                    $table->string('city', 100)->nullable();
                }
                if (! Schema::hasColumn('posts', 'mood')) {
                    $table->string('mood', 60)->nullable();
                }
                if (! Schema::hasColumn('posts', 'is_approved')) {
                    $table->boolean('is_approved')->default(true);
                }
                if (! Schema::hasColumn('posts', 'is_flagged')) {
                    $table->boolean('is_flagged')->default(false);
                }
                if (! Schema::hasColumn('posts', 'shares_count')) {
                    $table->unsignedInteger('shares_count')->default(0);
                }
            });
        }

        if (! Schema::hasTable('date_places')) {
            Schema::create('date_places', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('type', 50);
                $table->text('description')->nullable();
                $table->string('address', 255);
                $table->decimal('lat', 10, 8)->default(31.10480000);
                $table->decimal('lng', 11, 8)->default(77.17340000);
                $table->decimal('rating', 3, 2)->default(4.80);
                $table->string('cup_offer', 255)->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
            });
        }
    }

    public function down(): void
    {
        // Additive migration preserved
    }
};
