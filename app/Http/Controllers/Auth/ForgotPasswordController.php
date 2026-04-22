<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;

        // Check if user exists and has the correct role
        $user = User::where('email', $email)->first();
        if (!$user || !in_array($user->role, ['mahasiswa', 'dosen'])) {
            return back()->withErrors(['email' => 'Password reset hanya tersedia untuk mahasiswa dan dosen.']);
        }

        // Check if there's an active OTP for this email
        $activeOtp = PasswordResetOtp::where('email', $email)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->first();

        if ($activeOtp) {
            return back()->withErrors(['email' => 'An OTP has already been sent. Please wait for it to expire or use it.']);
        }

        // Generate OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        // Save OTP
        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send email
        Mail::to($email)->queue(new SendOtpMail($otp));

        return redirect()->route('password.verify.form', ['email' => $email])->with('success', 'OTP sent to your email.');
    }

    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
             'otp' => 'required|digits:6',
        ]);

        // Check if user exists and has the correct role
        $user = User::where('email', $request->email)->first();
        if (!$user || !in_array($user->role, ['mahasiswa', 'dosen'])) {
            return back()->withErrors(['email' => 'Password reset hanya tersedia untuk mahasiswa dan dosen.']);
        }

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Mark as used
        $otpRecord->update(['used_at' => now()]);

        return redirect()->route('password.reset.form', ['email' => $request->email])->with('success', 'OTP verified.');
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if user exists and has the correct role
        $user = User::where('email', $request->email)->first();
        if (!$user || !in_array($user->role, ['mahasiswa', 'dosen'])) {
            return back()->withErrors(['email' => 'Password reset hanya tersedia untuk mahasiswa dan dosen.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Clean up used OTPs
        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;

        // Check if user exists and has the correct role
        $user = User::where('email', $email)->first();
        if (!$user || !in_array($user->role, ['mahasiswa', 'dosen'])) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset hanya tersedia untuk mahasiswa dan dosen.'
            ]);
        }

        // Delete existing OTP for this email
        PasswordResetOtp::where('email', $email)->delete();

        // Generate new OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save new OTP
        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send email
        Mail::to($email)->queue(new SendOtpMail($otp));

        return response()->json(['success' => true, 'message' => 'OTP resent successfully.']);
    }
}
