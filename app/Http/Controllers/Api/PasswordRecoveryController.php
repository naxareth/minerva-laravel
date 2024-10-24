<?php

namespace App\Http\Controllers\Api;


use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp; // Import the Otp model
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordRecoveryController extends Controller
{
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User  not found',
            ], 404);
        }

        $otp = Str::random(6); // Generate a random 6-character OTP
        $expiresAt = now()->addMinutes(10); // Set expiration time

        // Create OTP entry
        Otp::create([
            'email' => $request->email,
            'otp' => $otp,
            'created_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        // Send the OTP to the user's email
        // Use Laravel Mail or a third-party service for sending emails
        // Example: Mail::to($request->email)->send(new OtpMail($otp));

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'message' => 'OTP sent successfully',
        ], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        // Find the latest OTP entry for the email
        $otpEntry = Otp::where('email', $request->email)->latest()->first();

        // Validate the OTP
        if (!$otpEntry || $otpEntry->expires_at < now() || $otpEntry->otp !== $request->otp) {
            return response()->json([
                'message' => 'Invalid or expired OTP',
            ], 401);
        }

        return response()->json([
            'message' => 'OTP verified successfully',
            // Optionally, you can return a token for further actions
        ], 200);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify the OTP
        $otpEntry = Otp::where('email', $request->email)->latest()->first();

        if (!$otpEntry || $otpEntry->expires_at < now() || $otpEntry->otp !== $request->otp) {
            return response()->json([
                'message' => 'Invalid or expired OTP',
            ], 401);
        }

        // Change the user's password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User  not found',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Optionally delete the OTP entry after successful password change
        $otpEntry->delete();

        return response()->json([
            'message' => 'Password changed successfully',
        ], 200);
    }
}
