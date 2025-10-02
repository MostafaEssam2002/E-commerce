<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageView;
use Illuminate\Support\Facades\Auth;

class TrackPageViews
{
    public function handle(Request $request, Closure $next)
    {
        // سجل الزيارة فقط للصفحات المهمة (مش الـ API أو admin)
        if (!$request->is('api/*') && !$request->is('admin/*')) {
            PageView::create([
                'page_url' => $request->fullUrl(),
                'page_title' => $request->route() ? $request->route()->getName() : $request->path(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => PageView::getDeviceType($request->userAgent()),
                'referrer' => $request->header('referer'),
                'viewed_at' => now(),
            ]);
        }

        return $next($request);
    }
}
