<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Checkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckupOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUser = Auth::user();
        $checkup = Checkup::findOrFail($request->id);

        if($checkup->user_id != $currentUser->id && $currentUser->role !== 'admin'){
            return response()->json(['message' => 'data tak ditemukan'], 404); 
        }

        return $next($request);
    }
}
