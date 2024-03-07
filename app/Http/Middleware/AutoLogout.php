<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class AutoLogout extends Middleware
{
    protected $timeout = 30; // Set the timeout in minutes.

    public function handle($request, Closure $next, ...$guards)
    {
        $response = parent::handle($request, $next, ...$guards);

        // Check if the user is authenticated
        if (Auth::check()) {
            $lastActivity = session('last_activity');

            // Check if the user has been inactive for the specified duration
            if (time() - $lastActivity > $this->timeout * 30) {
                Auth::logout();
                session()->flush();
                return redirect('/login'); //->with('logout_message', 'You have been logged out due to inactivity.');
            }

            // Update the last activity timestamp
            session(['last_activity' => time()]);
        }

        return $response;
    }
}
