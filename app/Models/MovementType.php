<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'is_positive',
    ];

    protected $casts = [
        'is_positive' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->hasMany(MovementCategory::class);
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('is_positive', true);
    }

    public function scopeExpense($query)
    {
        return $query->where('is_positive', false);
    }

    // Accessor for type name
    public function getTypeNameAttribute()
    {
        return $this->is_positive ? 'Income' : 'Expense';
    }

    protected static function booted()
    {
        static::creating(function ($post): void {
            if (auth()->check()) {
                $post->user_id = auth()->id();
            }
        });
    }
}
