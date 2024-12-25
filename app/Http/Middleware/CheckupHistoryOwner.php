<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CheckupHistory;
use App\Models\Checkup;

class CheckupHistoryOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if ($request->isMethod('post')) {
            $checkupId = $request->input('checkup_id');
            $checkup = Checkup::find($checkupId);
            
            if (!$checkup) {
                return response()->json(['message' => 'Checkup not found.'], 404);
            }

            if ($user->role === 'admin') {
                return $next($request);
            }

            if ($user->role === 'doctor' && $checkup->doctor_id === $user->id) {
                return $next($request);
            }

            if ($user->role === 'patient' && $checkup->patient_id === $user->id) {
                return response()->json(['message' => 'Patients are not allowed to create checkup history.'], 403);
            }
        }

        $id = $request->route('id');
        $history = CheckupHistory::with('checkup')->find($id);

        if (!$history) {
            return response()->json(['message' => 'Checkup history not found.'], 404);
        }

        $checkup = $history->checkup;

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === 'doctor' && $checkup->doctor_id === $user->id) {
            return $next($request);
        }

        if ($user->role === 'patient' && $request->isMethod('get') && $checkup->patient_id === $user->id) {
            return $next($request);
        }

        return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
    }
}
