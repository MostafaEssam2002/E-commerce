<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PageView extends Model
{
    protected $fillable = [
        'page_url',
        'page_title',
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'referrer',
        'viewed_at',
        'time_spent'
    ];

    protected $casts = [
        'viewed_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Accessor لعرض التاريخ بتوقيت مصر
    public function getViewedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Africa/Cairo');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getDeviceType($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }
}
