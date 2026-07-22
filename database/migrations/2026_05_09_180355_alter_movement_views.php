<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
                WHERE mt.is_positive = 1
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
                WHERE mt.is_positive = 0
                GROUP BY m.user_id, m.account_id, YEAR(m.date), MONTH(m.date);
        ');

        DB::statement('
            CREATE OR REPLACE VIEW expense_movements_by_category_month_year AS
                select
                    `m`.`user_id` AS `user_id`,
                    `m`.`account_id` AS `account_id`,
                    `mc`.`id` AS `category_id`,
                    `mc`.`name` AS `name`,
                    year(`m`.`date`) AS `year`,
                    month(`m`.`date`) AS `month`,
                    SUM(
                        case 
                            when `m`.`is_compensation` = 1 and `mt`.`is_positive` = 1 then `m`.`shared_amount` 
                            when `mt`.`is_positive` = 0 and `m`.`is_compensation` = 0 then `m`.`shared_amount` 
                            else (`m`.`shared_amount` * -1) 
                        END
                    ) AS `total_amount`
                from
                    `movements` `m`
                join `movement_categories` `mc` on
                    `m`.`movement_category_id` = `mc`.`id`
                join `movement_types` `mt` on
                    `mc`.`movement_type_id` = `mt`.`id`
                group by
                    `m`.`user_id`,
                    `m`.`account_id`,
                    `mc`.`id`,
                    `mc`.`name`,
                    year(`m`.`date`),
                    month(`m`.`date`);
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
