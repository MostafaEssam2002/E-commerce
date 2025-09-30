<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW categories_sales AS
            WITH categories_sales_cte AS (
                SELECT
                    p.category_id,
                    c.name AS category_name,
                    COUNT(*) AS total_orders
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN categories c ON p.category_id = c.id
                GROUP BY p.category_id, c.name
                ORDER BY MAX(od.order_id) DESC
            )
            SELECT * FROM categories_sales_cte ORDER BY category_id ;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS categories_sales");
    }
};
