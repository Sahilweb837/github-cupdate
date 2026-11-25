<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->unique();
                $table->string('password_hash');
                $table->string('full_name', 100);
                $table->date('dob')->default('1998-01-01');
                $table->enum('gender', ['male', 'female', 'nonbinary', 'other'])->default('other');
                $table->enum('preference', ['male', 'female', 'everyone'])->default('everyone');
                $table->text('bio')->nullable();
                $table->string('avatar')->nullable();
                $table->string('city', 100)->default('Shimla');
                $table->string('state', 100)->default('Himachal Pradesh');
                $table->text('interests')->nullable();
                $table->boolean('is_admin')->default(false);
                $table->enum('status', ['active', 'suspended'])->default('active');
                $table->enum('verification_status', ['unverified', 'pending', 'verified'])->default('unverified');
                $table->boolean('is_verified')->default(false);
                $table->boolean('is_premium')->default(false);
                $table->boolean('is_online')->default(false);
                $table->dateTime('last_active')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->default('user')->after('is_admin');
            }
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (! Schema::hasColumn('users', 'coffee_preference')) {
                $table->string('coffee_preference', 120)->default('Cappuccino');
            }
            if (! Schema::hasColumn('users', 'hobbies')) {
                $table->text('hobbies')->nullable();
            }
            if (! Schema::hasColumn('users', 'profile_visible')) {
                $table->boolean('profile_visible')->default(true);
            }
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
            if (! Schema::hasColumn('users', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->integer('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        // Non-destructive rollback on shared database
    }
};
