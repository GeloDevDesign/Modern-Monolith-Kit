<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email',
        'password', 'avatar', 'last_login', 'type', 'active',
    ];

    protected $appends = ['role', 'role_value', 'avatar_url', 'name', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserRole::class,
            'last_login' => 'datetime:M d, Y h:i A',
            'created_at' => 'date:Y-m-d',
            'active' => 'boolean',
        ];
    }

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getRoleAttribute(): string
    {
        return $this->type instanceof UserRole ? $this->type->label() : 'Unknown';
    }

    public function getRoleValueAttribute(): ?string
    {
        return $this->type instanceof UserRole ? $this->type->value : null;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    public function getStatusAttribute(): string
    {
        if ($this->deleted_at) {
            return 'Deleted';
        }

        return $this->active ? 'Active' : 'Inactive';
    }

    public function isAdmin(): bool
    {
        return $this->type === UserRole::TYPE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->type === UserRole::TYPE_SUPER_ADMIN;
    }

    public function isNormalUser(): bool
    {
        return $this->type === UserRole::TYPE_USER;
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->where('type', $role);
        });

        $query->when($filters['date_range'] ?? null, function ($query, $dateRange) {
            $query->whereBetween('created_at', $dateRange);
        });

        $query->when($filters['login_status'] ?? null, function ($query, $loginStatus) {
            $query->where('active', $loginStatus);
        });
    }

    public function scopeSort(Builder $query, $field, $order)
    {

        $direction = ($order == 1) ? 'asc' : 'desc';

        return match ($field) {

            'name' => $query->orderBy('first_name', $direction)
                ->orderBy('last_name', $direction),

            'role' => $query->orderBy('type', $direction),

            'status' => $query->orderBy('active', $direction),

            default => $query->orderBy($field ?? 'created_at', $direction),
        };
    }
}
