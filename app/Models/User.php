<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'currency_id',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // Relationships
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function movementTypes()
    {
        return $this->hasMany(MovementType::class);
    }

    public function movementCategories()
    {
        return $this->hasMany(MovementCategory::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function expectations()
    {
        return $this->hasMany(Expectation::class);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function accounts()
    {
        return $this->hasMany(Account::class)->orderBy('is_main', 'desc');
    }

    public function shared_accounts()
    {
        return $this->accounts()->where('is_shared', true);
    }

    // Filament access
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Or add your custom logic here
    }

    // Scope for admins
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    // Scope for regular users
    public function scopeRegularUsers($query)
    {
        return $query->where('is_admin', false);
    }

    public function getSharePercentage(int $accountId, float $share): float
    {
        $account = $this->shared_accounts()->find($accountId);

        if (!$account) {
            return 100;
        }

        // Ensure share is between 0 and 100
        return max(0, min(100, $share));
    }
}