<?php
// app/Http/Middleware/StoreDeleteRequestUrl.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class StoreDeleteRequestUrl
{
    public function handle($request, Closure $next)
    {
        Session::put('prev_url', url()->previous());
        Session::put('intended_url', url()->current());
            // Check if the user has confirmed their password
            if (!session('confirmed_password')) {
                return redirect()->route('confirm-password.show'); // Change to your confirm password route
            }
        return $next($request);
    }
}

