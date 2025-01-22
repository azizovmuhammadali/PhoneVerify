<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetlocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $locale = $request->header('Accept-Language');

    // Faqat qo'llab-quvvatlanadigan tillarni qabul qilish
    if (in_array($locale, ['uz', 'en', 'ru'])) {
        app()->setLocale($locale);
    } else {
        app()->setLocale('en'); // Default til
    }

    return $next($request);
    }
}
