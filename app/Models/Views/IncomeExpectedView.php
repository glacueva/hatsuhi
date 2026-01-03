<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class IncomeExpectedView extends Model {
    protected $table = 'income_budget_by_month_year';
    public $timestamps = false;
    public $incrementing = false;

    // These models should be read-only
    public function save(array $options = []) { return false; }
}
