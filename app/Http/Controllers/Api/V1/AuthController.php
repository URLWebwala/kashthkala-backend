<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $user = $this->authService->login($request->email, $request->password);

            $token = $user->createToken('api-token')->plainTextToken;

            $user->last_login_at = now();

            $user->is_verified = true;
            $user->save();

            $user = new \App\Http\Resources\UserResource($user);

            return apiSuccess('Login successful', [
                'user'  => $user,
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return apiError('Login failed', 422, 'Error', $e->errors());
        } catch (Throwable $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return apiError('Something went wrong', 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users,email'
            ],
            [
                'email.required' => 'Email field is required.',
                'email.email'    => 'Please enter a valid email address.',
                'email.exists'   => 'This email is not registered with us.',
            ]
        );

        try {
            $user = User::where('email', $request->email)->first();

            $token = Str::random(64);

            DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => now()
                ]
            );

            // $resetLink = url("/reset-password?token=$token&email={$user->email}");

            $resetLink = "http://localhost:5173/reset-password?token=$token&email={$user->email}";

            $subject = "Reset Password - URLWEBWALA";

            $body = view('emails.forgot-password', [
                'company'   => 'URLWEBWALA',
                'user'      => $user,
                'resetLink' => $resetLink,
            ])->render();

            $mailData = [
                'to'      => $user->email,
                'subject' => $subject,
                'html'    => $body,
            ];

            $result = sendMailSMTP($mailData);

            if (!$result['status']) {
                return apiError($result['error'] ?? 'SMTP Failed', 500);
            }

            return apiSuccess('Password reset link sent successfully');
        } catch (\Throwable $e) {
            \Log::error('Forgot Password Error: ' . $e->getMessage());
            return apiError('Something went wrong', 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:6|confirmed'
            ],
            [
                'email.required' => 'Email is required.',
                'email.email'    => 'Please enter a valid email address.',
                'token.required' => 'Reset token is missing or invalid.',
                'password.required'  => 'Password is required.',
                'password.min'       => 'Password must be at least 6 characters long.',
                'password.confirmed' => 'Password and confirm password do not match.',
            ]
        );

        try {
            $reset = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$reset) {
                return apiError('Invalid or expired reset link', 400);
            }

            if (Carbon::parse($reset->created_at)->addMinutes(10)->isPast()) {
                return apiError('Reset link expired', 400);
            }

            $user = User::where('email', $request->email)->first();
            $password = trim($request->password);
            $user->password = Hash::make($password);
            $user->save();

            DB::table('password_resets')->where('email', $request->email)->delete();

            return apiSuccess('Password reset successfully');
        } catch (\Throwable $e) {
            \Log::error('Reset Password Error: ' . $e->getMessage());
            return apiError('Something went wrong', 500);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return apiSuccess('Logout successful');
    }
}
