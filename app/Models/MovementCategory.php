<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movement_type_id',
        'name',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movementType()
    {
        return $this->belongsTo(MovementType::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function expectations()
    {
        return $this->hasMany(Expectation::class);
    }

    // Scopes
    public function scopeIncomeCategories($query)
    {
        return $query->whereHas('movementType', function ($q) {
            $q->where('is_positive', true);
        });
    }

    public function scopeExpenseCategories($query)
    {
        return $query->whereHas('movementType', function ($q) {
            $q->where('is_positive', false);
        });
    }

    // Accessor for full category name
    public function getFullNameAttribute()
    {
        $type = $this->movementType->name ?? 'Unknown';

        return "{$type} - {$this->name}";
    }

    protected static function booted()
    {
        static::creating(function ($post) {
            if (auth()->check()) {
                $post->user_id = auth()->id();
            }
        });
    }
}
