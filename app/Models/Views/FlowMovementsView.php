<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;
use App\Models\MovementCategory;
use App\Models\Account;

class FlowMovementsView extends Model {
    protected $table = 'flow_movements_by_month_year';
    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'positive_flow' => 'boolean',
        'date' => 'date',
    ];

    // These models should be read-only
    public function save(array $options = []) { return false; }

    public function category()
    {
        return $this->belongsTo(MovementCategory::class, 'movement_category_id')->where('user_id', auth()->id());
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->where('user_id', auth()->id());
    }
}
