<?php

namespace App\Http\Middleware;

use App\Enums\UserTypeEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check())
        {
            $user = auth()->user();

            if ($user->type === UserTypeEnum::Admin->value) {
                return to_route('admin.dashboard');
            }
            elseif ($user->type === UserTypeEnum::User->value) {
                return to_route('user.dashboard');
            }
            elseif ($user->type === UserTypeEnum::Volunteer->value) {
                return to_route('volunteer.dashboard');
            }
            elseif ($user->type === UserTypeEnum::LawEnforcement->value) {
                return to_route('law-enforcement.dashboard');
            }
        }

        return $next($request);
    }
}
