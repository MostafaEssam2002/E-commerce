<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class VisitsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray(); // كل الـ user_id
        $totalVisits = 10000;

        // الفترة الزمنية المطلوبة
        $startDate = Carbon::create(2025, 1, 1, 0, 0, 0);
        $endDate   = Carbon::create(2025, 12, 31, 23, 59, 59);

        $data = [];

        for ($i = 0; $i < $totalVisits; $i++) {
            $randomDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            $data[] = [
                'user_id'    => $users[array_rand($users)],
                'visited_at' => $randomDate->format('Y-m-d H:i:s'), // بصيغة MySQL DATETIME
            ];
        }

        // إدخال البيانات على دفعات
        $chunks = array_chunk($data, 500);
        foreach ($chunks as $chunk) {
            DB::table('visits')->insert($chunk);
        }
    }
}
