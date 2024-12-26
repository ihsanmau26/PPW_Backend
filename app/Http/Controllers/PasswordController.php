<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ForgotPasswordNotification;

class PasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $user = User::where('email', $request->email)->first();
        $user->notify(new ForgotPasswordNotification($token));

        return response()->json(['message' => 'Password reset link sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $messages = [
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
            'password.confirmed' => 'Password konfirmasi tidak cocok.',
        ];

        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'
        ], $messages);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return response()->json(['message' => 'Invalid or expired token.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password;
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil diperbarui.']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Periksa apakah kata sandi lama cocok
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'Kata sandi lama tidak sesuai.',
            ], 400);
        }

        // Update kata sandi baru dengan hashing
        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'message' => 'Kata sandi berhasil diubah.',
        ], 200);
    }
}