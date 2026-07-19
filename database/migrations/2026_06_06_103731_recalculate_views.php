<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE OR REPLACE VIEW income_movements_by_month_year AS
                SELECT 
                    m.user_id,
                    m.account_id,
                    YEAR(m.date) as year, 
                    MONTH(m.date) as month, 
                    SUM(m.shared_amount) as total_amount
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE (mt.is_positive = 1 and m.is_compensation = false) 
                    OR (mt.is_positive = 0 and m.is_compensation = true)
                GROUP BY m.user_id, m.account_id, YEAR(m.date), MONTH(m.date);
        ');

        DB::statement('
            CREATE OR REPLACE VIEW expense_movements_by_month_year AS
                SELECT 
                    m.user_id,
                    m.account_id,
                    YEAR(m.date) as year, 
                    MONTH(m.date) as month, 
                    SUM(m.shared_amount) as total_amount
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE (mt.is_positive = 0 and m.is_compensation = false)
                    OR (mt.is_positive = 1 and m.is_compensation = true)
                GROUP BY m.user_id, m.account_id, YEAR(m.date), MONTH(m.date);
        ');

        DB::statement('
            CREATE OR REPLACE VIEW expense_movements_by_category_month_year AS
                SELECT 
                    m.user_id,
                    m.account_id,
                    mc.id as category_id,
                    mc.name,
                    YEAR(m.date) as year, 
                    MONTH(m.date) as month, 
                    SUM(CASE 
                        WHEN mt.is_positive = 0 and is_compensation=0 THEN m.shared_amount
                        WHEN (mt.is_positive = 1 and m.is_compensation = 1) THEN m.shared_amount
                        ELSE -m.shared_amount
                    END) as total_amount
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                WHERE (mt.is_positive = 0)
                    OR (mt.is_positive = 1 and m.is_compensation = true)
                GROUP BY m.user_id, m.account_id, mc.id, mc.name, YEAR(m.date), MONTH(m.date)
                HAVING total_amount > 0;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS income_movements_by_month_year');
        DB::statement('DROP VIEW IF EXISTS expense_movements_by_month_year');
        DB::statement('DROP VIEW IF EXISTS expense_movements_by_category_month_year');
    }
};
