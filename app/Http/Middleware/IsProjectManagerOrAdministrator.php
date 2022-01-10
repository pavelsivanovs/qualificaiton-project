<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserStatus;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IsProjectManagerOrAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->status != UserStatus::STATUS_ADMIN && $user->status != UserStatus::STATUS_PM) {
            return redirect('/');
        }
        return $next($request);
    }
}
