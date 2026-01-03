<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short',
        'symbol',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Accessor for display
    public function getDisplayAttribute()
    {
        return "{$this->name} ({$this->short})";
    }
}
