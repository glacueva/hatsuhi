<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class ExpenseMovementCategoryView extends Model
{
    protected $table = 'expense_movements_by_category_month_year';

    public $timestamps = false;

    public $incrementing = false;

    // These models should be read-only
    public function save(array $options = [])
    {
        return false;
    }
}
