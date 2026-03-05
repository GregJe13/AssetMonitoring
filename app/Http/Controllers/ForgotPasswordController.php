<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('login.forgot-password');
    }

    /**
     * Send password reset link via email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate token
        $token = Str::random(64);

        // Store token in database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Get user name
        $user = User::where('email', $request->email)->first();

        // Send email
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        
        Mail::send('emails.reset-password', [
            'user' => $user,
            'resetUrl' => $resetUrl,
        ], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password - Monitoring Dashboard');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        
        // Verify token exists
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$tokenRecord) {
            return redirect()->route('login')->with('error', 'Link reset password tidak valid atau sudah expired.');
        }

        // Check if token matches
        if (!Hash::check($token, $tokenRecord->token)) {
            return redirect()->route('login')->with('error', 'Link reset password tidak valid.');
        }

        // Check if token is expired (1 hour)
        if (Carbon::parse($tokenRecord->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('login')->with('error', 'Link reset password sudah expired. Silakan request ulang.');
        }

        return view('login.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.exists' => 'Email tidak ditemukan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Verify token
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
            return back()->withErrors(['email' => 'Token tidak valid.'])->withInput();
        }

        // Check if token is expired (1 hour)
        if (Carbon::parse($tokenRecord->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.forgot')->with('error', 'Token sudah expired. Silakan request ulang.');
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
