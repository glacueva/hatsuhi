<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE OR REPLACE VIEW income_budget_by_month_year AS
                SELECT e.user_id, e.year, e.amount, mc.id as category_id, mc.name as category
                FROM expectations e
                JOIN movement_categories mc ON e.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE mt.is_positive = 1;
        ');

        DB::statement('
            CREATE OR REPLACE VIEW expenses_budget_by_month_year AS
                SELECT e.user_id, e.year, e.amount, mc.id as category_id, mc.name as category
                FROM expectations e
                JOIN movement_categories mc ON e.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE mt.is_positive = 0;
        ');

        DB::statement('
            CREATE OR REPLACE VIEW income_movements_by_month_year AS
                SELECT 
                    m.user_id,
                    YEAR(m.date) as year, 
                    MONTH(m.date) as month, 
                    SUM(m.amount) as total_amount
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE mt.is_positive = 1
                GROUP BY m.user_id, YEAR(m.date), MONTH(m.date);
        ');

        DB::statement('
            CREATE OR REPLACE VIEW expense_movements_by_month_year AS
                SELECT 
                    m.user_id,
                    YEAR(m.date) as year, 
                    MONTH(m.date) as month, 
                    SUM(m.amount) as total_amount
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE mt.is_positive = 0
                GROUP BY m.user_id, YEAR(m.date), MONTH(m.date);
        ');

        DB::statement('
            CREATE OR REPLACE VIEW expense_movements_by_category_month_year AS
                SELECT 
                    m.user_id,
                    mc.name,
                    YEAR(m.date) as year, 
                    MONTH(m.date) as month, 
                    SUM(m.amount) as total_amount
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE mt.is_positive = 0
                GROUP BY m.user_id, mc.name, YEAR(m.date), MONTH(m.date);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS income_budget_by_month_year');
        DB::statement('DROP VIEW IF EXISTS expenses_budget_by_month_year');
        DB::statement('DROP VIEW IF EXISTS income_movements_by_month_year');
        DB::statement('DROP VIEW IF EXISTS expense_movements_by_month_year');
        DB::statement('DROP VIEW IF EXISTS expense_movements_by_category_month_year');
    }
};
