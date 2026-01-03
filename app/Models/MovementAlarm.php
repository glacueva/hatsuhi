<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MovementAlarm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movement_category_id',
        'date',
        'concept',
        'amount',
        'is_repeatable',
        'periodicity_times',
        'periodicity_unit'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(MovementCategory::class, 'movement_category_id');
    }

    // Accessor for movement type
    public function getMovementTypeAttribute()
    {
        return $this->category->movementType ?? null;
    }

    // Accessor for formatted amount
    public function getFormattedAmountAttribute()
    {
        $currencySymbol = $this->user->currency->symbol ?? '$';
        $amount = number_format($this->amount, 2);
        
        return $this->isPositive() ? 
            "+{$currencySymbol}{$amount}" : 
            "-{$currencySymbol}{$amount}";
    }

    // Helper method to check if movement is positive
    public function isPositive()
    {
        return $this->category->movementType->is_positive ?? false;
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->whereHas('category.movementType', function ($q) {
            $q->where('is_positive', true);
        });
    }

    public function scopeExpense($query)
    {
        return $query->whereHas('category.movementType', function ($q) {
            $q->where('is_positive', false);
        });
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    protected static function booted() { 
        static::creating(function ($post) { 
            if (auth()->check()) { $post->user_id = auth()->id(); } 
        }); 
    }
}