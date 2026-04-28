<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $password): User
    {
        $user = User::where(User::EMAIL, $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        if ($user->status !== StatusEnum::ACTIVE) {
            throw ValidationException::withMessages([
                'account' => ['Your account is inactive. Please contact admin.'],
            ]);
        }

        $user->update([User::LAST_LOGIN_AT => now()]);

        $this->createLoginHistory($user, request());

        return $user;
    }

    private function createLoginHistory($user, $request)
    {
        LoginHistory::create([
            LoginHistory::USER_ID => $user->id,
            LoginHistory::IP_ADDRESS => $request->ip(),
            LoginHistory::USER_AGENT => $request->userAgent(),
            LoginHistory::LOGIN_AT => now(),
            LoginHistory::DEVICE_TYPE => $this->getDeviceType($request->userAgent()),
            LoginHistory::LOCATION => $this->getCityFromIp($request->ip()),
        ]);
    }

    private function getDeviceType($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'tablet';
        }

        return 'desktop';
    }

    public function getCityFromIp($ip)
    {
        $response = Http::withoutVerifying()
            ->get("https://ipinfo.io/{$ip}/json");

        return $response->json('city') ?? 'Unknown';
    }

    public function logout($user)
    {
        $loginHistory = LoginHistory::where(LoginHistory::USER_ID, $user->id)
            ->whereNull(LoginHistory::LOGOUT_AT)
            ->latest()
            ->first();

        if ($loginHistory) {
            $loginHistory->update([LoginHistory::LOGOUT_AT => now()]);
        }

        return $user->currentAccessToken()->delete();
    }
}
