<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $products = Product::all();
        $totalOrders = 100;
        $startDate = Carbon::create(2025, 1, 1, 0, 0, 0);
        $endDate   = Carbon::create(2025, 12, 31, 23, 59, 59);
        for ($i = 0; $i < $totalOrders; $i++) {
            // تاريخ عشوائي للـ order
            $randomDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );
            // إدخال order
            $orderId = DB::table('orders')->insertGetId([
                'name'       => 'Customer ' . ($i + 1),
                'email'      => 'customer' . ($i + 1) . '@example.com',
                'address'    => 'Random Address ' . rand(1, 100),
                'phone'      => '0100' . rand(1000000, 9999999),
                'note'       => 'Test order ' . ($i + 1),
                'user_id'    => $users[array_rand($users)],
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
            // عدد منتجات في الـ order
            // $numProducts = rand(1, 5);
            // $selectedProducts = $products->random($numProducts);
            $numProducts = rand(1, min(5, $products->count()));
            $selectedProducts = $products->random($numProducts);
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                DB::table('order_details')->insert([
                    'order_id'   => $orderId,
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $product->price * $quantity,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}
