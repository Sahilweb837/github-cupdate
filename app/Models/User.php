<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const ADMIN_ROLES = ['super_admin', 'moderator', 'support_admin', 'content_manager'];
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'full_name',
        'email',
        'email_verified_at',
        'password_hash',
        'google_id',
        'dob',
        'gender',
        'preference',
        'role',
        'status',
        'city',
        'state',
        'coffee_preference',
        'bio',
        'interests',
        'hobbies',
        'avatar',
        'profile_visible',
        'is_admin',
        'is_verified',
        'verification_status',
        'is_premium',
        'premium_until',
        'is_online',
        'last_active',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
        'google_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'dob' => 'date',
            'last_active' => 'datetime',
            'is_admin' => 'boolean',
            'is_verified' => 'boolean',
            'is_premium' => 'boolean',
            'is_online' => 'boolean',
            'profile_visible' => 'boolean',
        ];
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin || in_array($this->role, self::ADMIN_ROLES, true);
    }

    public function hasAdminRole(string ...$roles): bool
    {
        if ($this->role === 'super_admin' || ($this->is_admin && ($this->role === 'user' || !$this->role))) {
            return true;
        }

        return in_array($this->role, $roles, true);
    }

    public function firstName(): string
    {
        return explode(' ', trim((string) $this->full_name))[0] ?: 'Member';
    }

    public function ageNumber(): ?int
    {
        if (! $this->dob) {
            return null;
        }

        $age = Carbon::parse($this->dob)->age;
        return ($age >= 18 && $age <= 90) ? $age : null;
    }

    public function avatarUrl(): string
    {
        $raw = html_entity_decode((string) ($this->avatar ?? ''));
        if ($raw === '' || $raw === 'default_avatar.jpg') {
            return asset('images/cafe-blue-bottle.jpg');
        }
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }

        return asset(ltrim($raw, '/'));
    }

    public function interestsList(): array
    {
        $raw = (string) ($this->interests ?? '');
        if ($raw === '') {
            return [];
        }
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    public function hobbiesList(): array
    {
        $raw = (string) ($this->hobbies ?? '');
        if ($raw === '') {
            return [];
        }
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    public function compatibilityWith(?User $other): int
    {
        if (! $other) {
            return 78;
        }
        $score = 68;
        if (strcasecmp($this->city ?? '', $other->city ?? '') === 0) {
            $score += 14;
        }
        $shared = collect($this->interestsList())->intersect($other->interestsList())->count();
        $score += min(16, $shared * 6);

        return min(98, $score);
    }
}
