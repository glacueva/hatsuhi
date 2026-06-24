<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expectation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movement_category_id',
        'year',
        'amount',
    ];

    protected $casts = [
        'year' => 'integer',
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

    // Accessor for monthly amount
    public function getMonthlyAmountAttribute()
    {
        return $this->amount / 12;
    }

    // Accessor for formatted amount
    public function getFormattedAmountAttribute()
    {
        $currencySymbol = $this->user->currency->symbol ?? '$';

        return $currencySymbol.number_format($this->amount, 2);
    }

    // Scopes
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeIncomeExpectations($query)
    {
        return $query->whereHas('category.movementType', function ($q): void {
            $q->where('is_positive', true);
        });
    }

    public function scopeExpenseExpectations($query)
    {
        return $query->whereHas('category.movementType', function ($q): void {
            $q->where('is_positive', false);
        });
    }

    // Validation rules for creating/updating
    public static function rules($id = null)
    {
        return [
            'movement_category_id' => 'required|exists:movement_categories,id',
            'year' => 'required|integer|min:2000|max:2100',
            'amount' => 'required|numeric|min:0',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($expectation): void {
            // Ensure unique constraint at application level too
            $existing = self::where('user_id', $expectation->user_id)
                ->where('movement_category_id', $expectation->movement_category_id)
                ->where('year', $expectation->year)
                ->where('id', '!=', $expectation->id)
                ->first();

            if ($existing) {
                throw new \Exception('Expectation for this category and year already exists.');
            }
        });
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
