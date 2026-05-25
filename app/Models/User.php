<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_WORKER = 'worker';
    const ROLE_GUEST = 'guest';

    /**
     * Role hierarchy from highest to lowest authority.
     */
    const ROLE_HIERARCHY = [
        self::ROLE_ADMIN,
        self::ROLE_MANAGER,
        self::ROLE_WORKER,
        self::ROLE_GUEST,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Role Helpers ───────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isWorker(): bool
    {
        return $this->role === self::ROLE_WORKER;
    }

    public function isGuest(): bool
    {
        return $this->role === self::ROLE_GUEST;
    }

    /**
     * Check if user has at least the given minimum role level.
     * e.g. hasMinRole('worker') returns true for worker, manager, admin.
     */
    public function hasMinRole(string $minRole): bool
    {
        $userLevel = array_search($this->role, self::ROLE_HIERARCHY);
        $requiredLevel = array_search($minRole, self::ROLE_HIERARCHY);

        // Unknown roles get lowest priority
        if ($userLevel === false) $userLevel = count(self::ROLE_HIERARCHY);
        if ($requiredLevel === false) $requiredLevel = count(self::ROLE_HIERARCHY);

        return $userLevel <= $requiredLevel;
    }

    /**
     * Get the display label for this user's role.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_WORKER => 'Worker',
            self::ROLE_GUEST => 'Guest',
            default => ucfirst($this->role),
        };
    }
}

