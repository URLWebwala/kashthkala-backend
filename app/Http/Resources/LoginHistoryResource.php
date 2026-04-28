<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userAgent = $this->user_agent ?? '';
        $browser = $this->parseBrowser($userAgent);
        $platform = $this->parsePlatform($userAgent);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->when($this->user, function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'device_type' => $this->device_type ?? 'Desktop',
            'browser' => $browser,
            'platform' => $platform,
            'location' => $this->location ?? 'Unknown',
            'login_at' => $this->login_at?->toISOString(),
            'logout_at' => $this->logout_at?->toISOString(),
            'status' => 'success',
            'created_at' => $this->created_at?->toISOString(),
        ];
    }

    /**
     * Parse browser from user agent
     */
    private function parseBrowser(string $userAgent): string
    {
        if (preg_match('/Firefox\/([0-9.]+)/', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Chrome\/([0-9.]+)/', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent) && !preg_match('/Chrome/', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/MSIE|Trident/', $userAgent)) {
            return 'Internet Explorer';
        }
        return 'Unknown';
    }

    /**
     * Parse platform from user agent
     */
    private function parsePlatform(string $userAgent): string
    {
        if (preg_match('/Windows NT ([0-9.]+)/', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Mac OS X ([0-9_]+)/', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/Android ([0-9.]+)/', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            return 'iOS';
        }
        return 'Unknown';
    }
}
 