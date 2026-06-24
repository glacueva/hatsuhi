<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'balance',
        'is_main',
        'is_shared',
        'share',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
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
