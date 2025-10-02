<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class PageViewsTableSeeder extends Seeder
{
    public function run()
    {
        // جلب IDs المستخدمين من 1 إلى 100
        $userIds = User::whereBetween('id', [1, 100])->pluck('id')->toArray();
        
        if (empty($userIds)) {
            $this->command->error('لا يوجد مستخدمين في قاعدة البيانات!');
            return;
        }

        // الصفحات المتاحة من web.php
        $pages = [
            ['url' => '/', 'title' => 'Home Page'],
            ['url' => '/category', 'title' => 'Categories'],
            ['url' => '/category/1', 'title' => 'Electronics'],
            ['url' => '/category/2', 'title' => 'Watches'],
            ['url' => '/category/3', 'title' => 'Bags'],
            ['url' => '/category/4', 'title' => 'Makeup'],
            ['url' => '/category/5', 'title' => 'Cameras'],
            ['url' => '/category/6', 'title' => 'Food'],
            ['url' => '/product/1', 'title' => 'Product - Green apples'],
            ['url' => '/product/2', 'title' => 'Product - Camera canon 14'],
            ['url' => '/product/3', 'title' => 'Product - Laptop'],
            ['url' => '/product/4', 'title' => 'Product - Nike Running Shoes'],
            ['url' => '/product/5', 'title' => 'Product - Smart Watch'],
            ['url' => '/reviews', 'title' => 'Reviews'],
            ['url' => '/cart', 'title' => 'Shopping Cart'],
            ['url' => '/products', 'title' => 'All Products'],
            ['url' => '/productstable', 'title' => 'Products Table'],
            ['url' => '/lastorders', 'title' => 'Last Orders'],
        ];

        // أنواع الأجهزة
        $deviceTypes = ['desktop', 'mobile', 'tablet'];

        // User Agents لكل جهاز
        $userAgents = [
            'desktop' => [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            ],
            'mobile' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
                'Mozilla/5.0 (Linux; Android 13) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36',
                'Mozilla/5.0 (Linux; Android 12; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
            ],
            'tablet' => [
                'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
                'Mozilla/5.0 (Linux; Android 13; SM-T870) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ],
        ];

        // مصادر الزيارات (Referrers)
        $referrers = [
            null, // Direct traffic
            null,
            null,
            'https://www.google.com',
            'https://www.facebook.com',
            'https://www.instagram.com',
            'https://twitter.com',
            'https://www.youtube.com',
        ];

        // الفترة الزمنية
        $startDate = Carbon::create(2025, 1, 1, 0, 0, 0);
        $endDate = Carbon::create(2025, 12, 31, 23, 59, 59);

        // عدد المشاهدات المطلوبة
        $totalPageViews = 10000;

        $data = [];

        $this->command->info('جاري إنشاء بيانات PageViews...');

        for ($i = 0; $i < $totalPageViews; $i++) {
            // تاريخ عشوائي
            $randomDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            // صفحة عشوائية
            $page = $pages[array_rand($pages)];

            // نوع جهاز عشوائي (مع تفضيل Mobile)
            $deviceWeights = ['desktop' => 30, 'mobile' => 50, 'tablet' => 20];
            $randomDevice = $this->weightedRandom($deviceWeights);

            // User Agent حسب نوع الجهاز
            $userAgent = $userAgents[$randomDevice][array_rand($userAgents[$randomDevice])];

            // IP عشوائي
            $ipAddress = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);

            // مستخدم عشوائي (50% مسجل دخول، 50% زائر)
            $userId = (rand(1, 100) <= 50 && !empty($userIds)) ? $userIds[array_rand($userIds)] : null;

            // وقت قضاه في الصفحة (10 ثانية إلى 10 دقائق)
            $timeSpent = rand(10, 600);

            $data[] = [
                'page_url' => $page['url'],
                'page_title' => $page['title'],
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'device_type' => $randomDevice,
                'referrer' => $referrers[array_rand($referrers)],
                'viewed_at' => $randomDate->format('Y-m-d H:i:s'),
                'time_spent' => $timeSpent,
                'created_at' => $randomDate->format('Y-m-d H:i:s'),
                'updated_at' => $randomDate->format('Y-m-d H:i:s'),
            ];

            // عرض التقدم كل 1000 صف
            if (($i + 1) % 1000 == 0) {
                $this->command->info('تم إنشاء ' . ($i + 1) . ' من ' . $totalPageViews);
            }
        }

        // إدخال البيانات على دفعات
        $this->command->info('جاري إدخال البيانات في قاعدة البيانات...');
        $chunks = array_chunk($data, 500);
        
        foreach ($chunks as $index => $chunk) {
            DB::table('page_views')->insert($chunk);
            $this->command->info('تم إدخال الدفعة ' . ($index + 1) . ' من ' . count($chunks));
        }

        $this->command->info('تم إضافة ' . $totalPageViews . ' مشاهدة صفحة بنجاح!');
    }

    /**
     * اختيار عنصر عشوائي بناءً على الأوزان
     */
    private function weightedRandom($weights)
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        
        foreach ($weights as $key => $weight) {
            $random -= $weight;
            if ($random <= 0) {
                return $key;
            }
        }
        
        return array_key_first($weights);
    }
}
