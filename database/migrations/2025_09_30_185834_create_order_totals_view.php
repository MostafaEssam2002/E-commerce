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
        DB::statement("
            CREATE OR REPLACE VIEW order_totals_view AS
            WITH cte AS (
                SELECT
                    od.order_id,
                    SUM(p.shipping) AS total_shipping,
                    SUM(od.quantity * od.price) AS total_price,
                    od.created_at
                FROM order_details od
                JOIN products p ON p.id = od.product_id
                GROUP BY od.order_id, od.created_at
                ORDER BY od.order_id DESC
            )
            SELECT
                *,
                total_shipping + total_price AS total_order
            FROM cte
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS order_totals_view");
    }
};
