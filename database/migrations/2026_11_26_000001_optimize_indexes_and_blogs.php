<?php

use App\Data\SeoGuides;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Composite indexes for fast queries at 10,000+ users scale
        $this->addIndexSafe('users', ['status', 'profile_visible', 'is_admin', 'is_online'], 'idx_users_discovery_fast');
        $this->addIndexSafe('users', ['city', 'status'], 'idx_users_city_status');
        $this->addIndexSafe('users', ['state', 'status'], 'idx_users_state_status');
        $this->addIndexSafe('messages', ['sender_id', 'receiver_id', 'created_at'], 'idx_messages_thread');
        $this->addIndexSafe('messages', ['receiver_id', 'is_read', 'created_at'], 'idx_messages_unread');
        $this->addIndexSafe('posts', ['is_approved', 'is_flagged', 'created_at'], 'idx_posts_feed');
        $this->addIndexSafe('cafe_reviews', ['is_approved', 'is_flagged', 'city'], 'idx_cafe_reviews_feed');
        $this->addIndexSafe('visitor_logs', ['created_at', 'session_id'], 'idx_visitor_logs_daily');

        // Seed curated SEO blog guides into blog_posts when table exists
        if (Schema::hasTable('blog_posts')) {
            foreach (SeoGuides::all() as $guide) {
                $exists = DB::table('blog_posts')->where('slug', $guide['slug'])->exists();
                if (! $exists) {
                    DB::table('blog_posts')->insert([
                        'title' => $guide['h1'],
                        'slug' => $guide['slug'],
                        'excerpt' => $guide['description'],
                        'body' => implode("\n\n", $guide['paragraphs']),
                        'cover_image' => null,
                        'meta_title' => $guide['title'],
                        'meta_description' => mb_substr($guide['description'], 0, 250),
                        'is_published' => true,
                        'published_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Non-destructive index/content migration
    }

    /**
     * @param array<int, string> $columns
     */
    private function addIndexSafe(string $table, array $columns, string $indexName): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }
        foreach ($columns as $col) {
            if (! Schema::hasColumn($table, $col)) {
                return;
            }
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
                $blueprint->index($columns, $indexName);
            });
        } catch (Throwable) {
            // Index already exists on MySQL/SQLite
        }
    }
};
