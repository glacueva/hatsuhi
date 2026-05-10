<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'user_id',
        'movement_category_id',
        'date',
        'concept',
        'amount',
        'share',
        'shared_amount',
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
        return $this->belongsTo(MovementCategory::class, 'movement_category_id')->where('user_id', auth()->id());
    }

    public function movementType()
    {
        return $this->hasOneThrough(MovementType::class, MovementCategory::class,
            'id','id',
            'movement_category_id','movement_type_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->where('user_id', auth()->id());
    }

    public function getAbsoluteAmountAttribute(): float
    {
        return abs($this->amount);
    }
    public function getAbsoluteSharedAmountAttribute(): float
    {
        return abs($this->shared_amount);
    }


    // accessor for compensation
    public function getPositiveFlowAttribute(): bool
    {
        // Compensation because would normally be positive but is negative
        if ($this->isCompensation() && $this->isPositive()) {
            return false; 

        // Compensation because would normally be negative but is positive
        } elseif ($this->isCompensation() && !$this->isPositive()) {
            return true;
        // Not a compensation, but is positive
        } elseif (!$this->isCompensation() && $this->isPositive()) {
            return true;
        }
        // Not a compensation, but is negative
        return false;
    }


    public function getCompensationAttribute(): bool
    {
        return $this->isCompensation();
    }

    // accessor for compensation
    public function getIsCompensationAttribute(): string
    {
        return $this->isCompensation() ? 'Yes' : 'No';
    }

     // Helper method to check if movement is a compensation
    public function isCompensation(): bool
    {
        return $this->amount < 0;
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