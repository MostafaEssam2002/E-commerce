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
        // الصفحات المتاحة من web.php مع أوزان الشعبية
        $pages = [
            ['url' => '/', 'title' => 'Home Page', 'weight' => 25],
            ['url' => '/category', 'title' => 'Categories', 'weight' => 15],
            ['url' => '/category/1', 'title' => 'Electronics', 'weight' => 10],
            ['url' => '/category/2', 'title' => 'Watches', 'weight' => 8],
            ['url' => '/category/3', 'title' => 'Bags', 'weight' => 7],
            ['url' => '/category/4', 'title' => 'Makeup', 'weight' => 6],
            ['url' => '/category/5', 'title' => 'Cameras', 'weight' => 5],
            ['url' => '/category/6', 'title' => 'Food', 'weight' => 8],
            ['url' => '/category/7', 'title' => 'Furniture', 'weight' => 4],
            ['url' => '/category/8', 'title' => 'Clothing', 'weight' => 9],
            ['url' => '/product/1', 'title' => 'Product - Green apples', 'weight' => 8],
            ['url' => '/product/2', 'title' => 'Product - Camera canon 14', 'weight' => 7],
            ['url' => '/product/3', 'title' => 'Product - Laptop', 'weight' => 10],
            ['url' => '/product/4', 'title' => 'Product - Nike Running Shoes', 'weight' => 9],
            ['url' => '/product/5', 'title' => 'Product - Smart Watch', 'weight' => 12],
            ['url' => '/product/6', 'title' => 'Product - Leather Handbag', 'weight' => 6],
            ['url' => '/product/7', 'title' => 'Product - Bluetooth Speaker', 'weight' => 8],
            ['url' => '/product/8', 'title' => 'Product - Office Chair', 'weight' => 5],
            ['url' => '/product/9', 'title' => 'Product - Men T-Shirt', 'weight' => 7],
            ['url' => '/product/10', 'title' => 'Product - Toy Car', 'weight' => 4],
            ['url' => '/reviews', 'title' => 'Reviews', 'weight' => 5],
            ['url' => '/cart', 'title' => 'Shopping Cart', 'weight' => 10],
            ['url' => '/products', 'title' => 'All Products', 'weight' => 12],
            ['url' => '/productstable', 'title' => 'Products Table', 'weight' => 3],
            ['url' => '/lastorders', 'title' => 'Last Orders', 'weight' => 4],
        ];
        // حساب مجموع الأوزان
        $totalWeight = array_sum(array_column($pages, 'weight'));
        // أنواع الأجهزة
        $deviceTypes = ['desktop', 'mobile', 'tablet'];
        // User Agents لكل جهاز
        $userAgents = [
            'desktop' => [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ],
            'mobile' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
                'Mozilla/5.0 (Linux; Android 13) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36',
                'Mozilla/5.0 (Linux; Android 12; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
                'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1',
            ],
            'tablet' => [
                'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
                'Mozilla/5.0 (Linux; Android 13; SM-T870) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (iPad; CPU OS 16_0 like Mac OS X) AppleWebKit/605.1.15 Safari/604.1',
            ],
        ];

        // مصادر الزيارات (Referrers)
        $referrers = [
            null, null, null, null, null, // 50% Direct traffic
            'https://www.google.com',
            'https://www.google.com/search',
            'https://www.facebook.com',
            'https://www.instagram.com',
            'https://twitter.com',
        ];

        // الفترة الزمنية
        $startDate = Carbon::create(2025, 1, 1, 0, 0, 0);
        $endDate = Carbon::create(2025, 12, 31, 23, 59, 59);

        // عدد المشاهدات الكلي (50,000 لبيانات واضحة)
        $totalPageViews = 50000;

        $this->command->info('جاري إنشاء ' . number_format($totalPageViews) . ' مشاهدة صفحة...');
        $this->command->info('هذا قد يستغرق بضع دقائق...');

        $data = [];
        $batchCounter = 0;

        for ($i = 0; $i < $totalPageViews; $i++) {
            // توزيع واقعي على مدار السنة
            // زيادة الزيارات في بعض الأشهر (موسم الأعياد، العطلات)
            $month = $this->getWeightedMonth();

            // توزيع واقعي على أيام الأسبوع (أقل في عطلة نهاية الأسبوع)
            $dayOfWeek = rand(1, 100);
            if ($dayOfWeek <= 70) {
                // أيام العمل (الاثنين-الجمعة) - 70% من الزيارات
                $dayInMonth = rand(1, 28);
            } else {
                // عطلة نهاية الأسبوع - 30% من الزيارات
                $dayInMonth = rand(1, 28);
            }

            // توزيع الساعات (أكثر نشاطاً من 9 صباحاً حتى 11 مساءً)
            $hour = $this->getWeightedHour();
            $minute = rand(0, 59);
            $second = rand(0, 59);

            try {
                $randomDate = Carbon::create(2025, $month, min($dayInMonth, 28), $hour, $minute, $second);
            } catch (\Exception $e) {
                $randomDate = Carbon::create(2025, $month, 1, $hour, $minute, $second);
            }

            // اختيار صفحة بناءً على الوزن
            $page = $this->getWeightedPage($pages, $totalWeight);

            // نوع جهاز عشوائي (مع تفضيل Mobile)
            $deviceWeights = ['desktop' => 25, 'mobile' => 60, 'tablet' => 15];
            $randomDevice = $this->weightedRandom($deviceWeights);

            // User Agent حسب نوع الجهاز
            $userAgent = $userAgents[$randomDevice][array_rand($userAgents[$randomDevice])];

            // IP عشوائي
            $ipAddress = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);

            // مستخدم عشوائي (40% مسجل دخول، 60% زائر)
            $userId = (rand(1, 100) <= 40 && !empty($userIds)) ? $userIds[array_rand($userIds)] : null;

            // وقت قضاه في الصفحة (توزيع واقعي)
            $timeSpent = $this->getRealisticTimeSpent($page['url']);

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

            // إدخال البيانات كل 1000 صف لتجنب استهلاك الذاكرة
            if (count($data) >= 1000) {
                DB::table('page_views')->insert($data);
                $batchCounter += count($data);
                $data = [];
                $this->command->info('تم إدخال ' . number_format($batchCounter) . ' من ' . number_format($totalPageViews));
            }
        }

        // إدخال الباقي
        if (!empty($data)) {
            DB::table('page_views')->insert($data);
            $batchCounter += count($data);
            $this->command->info('تم إدخال ' . number_format($batchCounter) . ' من ' . number_format($totalPageViews));
        }

        $this->command->info('✓ تم إضافة ' . number_format($totalPageViews) . ' مشاهدة صفحة بنجاح!');
        $this->command->info('✓ البيانات موزعة على السنة كاملة مع نمط واقعي');
    }

    /**
     * اختيار شهر بناءً على الأوزان (بعض الأشهر أكثر نشاطاً)
     */
    private function getWeightedMonth()
    {
        // زيادة النشاط في: يناير (عروض)، رمضان (مايو)، نوفمبر (Black Friday)، ديسمبر (أعياد)
        $monthWeights = [
            1 => 12,  // يناير
            2 => 8,   // فبراير
            3 => 8,   // مارس
            4 => 9,   // أبريل
            5 => 11,  // مايو (رمضان)
            6 => 7,   // يونيو
            7 => 7,   // يوليو
            8 => 6,   // أغسطس
            9 => 8,   // سبتمبر
            10 => 9,  // أكتوبر
            11 => 10, // نوفمبر (Black Friday)
            12 => 11, // ديسمبر (أعياد)
        ];

        return $this->weightedRandom($monthWeights);
    }

    /**
     * اختيار ساعة بناءً على نمط استخدام واقعي
     */
    private function getWeightedHour()
    {
        $hourWeights = [
            0 => 2, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 2,
            6 => 3, 7 => 5, 8 => 8, 9 => 10, 10 => 12, 11 => 13,
            12 => 11, 13 => 10, 14 => 11, 15 => 12, 16 => 13,
            17 => 14, 18 => 13, 19 => 12, 20 => 15, 21 => 14,
            22 => 10, 23 => 5,
        ];

        return $this->weightedRandom($hourWeights);
    }

    /**
     * حساب وقت واقعي بناءً على نوع الصفحة
     */
    private function getRealisticTimeSpent($pageUrl)
    {
        if (strpos($pageUrl, '/product/') !== false) {
            // صفحات المنتجات: 30 ثانية إلى 5 دقائق
            return rand(30, 300);
        } elseif (strpos($pageUrl, '/cart') !== false) {
            // صفحة السلة: 1-8 دقائق
            return rand(60, 480);
        } elseif ($pageUrl === '/') {
            // الصفحة الرئيسية: 15 ثانية إلى 3 دقائق
            return rand(15, 180);
        } elseif (strpos($pageUrl, '/category') !== false) {
            // صفحات الأقسام: 20 ثانية إلى 4 دقائق
            return rand(20, 240);
        } else {
            // باقي الصفحات: 10 ثانية إلى 3 دقائق
            return rand(10, 180);
        }
    }

    /**
     * اختيار صفحة بناءً على الوزن
     */
    private function getWeightedPage($pages, $totalWeight)
    {
        $random = rand(1, $totalWeight);

        foreach ($pages as $page) {
            $random -= $page['weight'];
            if ($random <= 0) {
                return $page;
            }
        }

        return $pages[0];
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
