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
        $users = User::pluck('id')->toArray(); // كل الـ user_id الموجودة
        $totalVisits = 10000;
        $startDate = Carbon::now()->subYear(); // من السنة اللي فاتت
        $endDate = Carbon::now();
        $data = [];
        for ($i = 0; $i < $totalVisits; $i++) {
            $randomDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );
            $data[] = [
                'user_id' => $users[array_rand($users)], // اختيار user_id موجود فعلاً
                'visited_at' => $randomDate,
            ];
        }
        // نضيف البيانات bulk insert عشان الأداء
        $chunks = array_chunk($data, 500);
        foreach ($chunks as $chunk) {
            DB::table('visits')->insert($chunk);
        }
    }
}
