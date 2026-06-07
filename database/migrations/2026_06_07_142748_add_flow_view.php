<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::statement('
            CREATE OR REPLACE VIEW flow_movements_by_month_year AS
                SELECT 
                    m.id,
                    m.user_id,
                    m.account_id,
                    movement_category_id,
                    mc.name as category_name,
                    movement_type_id,
                    mt.name as movement_type_name,
                    a.name as account_name,
                    m.date,
                    m.concept,
                    (
                        CASE WHEN 
                            (mt.is_positive = 1 and m.is_compensation = false) OR 
                            (mt.is_positive = 0 and m.is_compensation = true) 
                            THEN true 
                        ELSE 
                            false
                        END
                    ) as positive_flow,
                    (
                        CASE WHEN 
                            (mt.is_positive = 1 and m.is_compensation = false) OR 
                            (mt.is_positive = 0 and m.is_compensation = true) 
                            THEN m.amount 
                        ELSE 
                            -m.amount 
                        END
                    ) as amount,
                    (
                        CASE WHEN 
                            (mt.is_positive = 1 and m.is_compensation = false) OR 
                            (mt.is_positive = 0 and m.is_compensation = true) 
                            THEN m.shared_amount 
                        ELSE 
                            -m.shared_amount 
                        END
                    ) as shared_amount,
                c.short as currency_short,
                m.share,
                m.is_compensation,
                m.created_at,
                m.updated_at
                FROM movements m
                JOIN movement_categories mc ON m.movement_category_id = mc.id
                JOIN movement_types mt ON mc.movement_type_id = mt.id
                JOIN accounts a ON m.account_id = a.id
                JOIN users u ON m.user_id = u.id
                JOIN currencies c ON u.currency_id = c.id;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::statement('DROP VIEW IF EXISTS flow_movements_by_month_year');
    }
};
