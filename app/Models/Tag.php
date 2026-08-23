<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movements()
    {
        return $this->belongsToMany(
            Movement::class,
            'movement_tags'
        );
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
