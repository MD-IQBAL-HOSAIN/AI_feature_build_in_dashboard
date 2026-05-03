<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    /**
     * Generate OTP and store it temporarily
     */
    protected $ttl = 300;

    private function setTtl(int $ttl){
        $this->ttl = $ttl;
    }
    public function getTtl(){
        return $this->ttl;
    }
    public function getTtl_min_time(){
        return floor($this->getTtl() / 60);
    }
    public function generateOtp(string $key, int $ttl = null): string
    {
        if(!is_null($ttl))
            $this->setTtl($ttl);
        $otp = random_int(100000, 999999); // 6-digit OTP
        if(!request()->is('api/*'))
            Cache::put($key, $otp, $ttl); // store in cache for $ttl seconds
        return $otp;
    }

    /**
     * Send OTP via email
     */
    public function sendOtpEmail(string $email, string $otp )
    {
        Mail::to($email)->send(new OtpMail(otp: $otp, ttl: $this->getTtl_min_time()));
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(string $key, string $otp): bool
    {
        $cachedOtp = Cache::get($key);
        if ($cachedOtp && $cachedOtp == $otp) {
            Cache::forget($key); // remove OTP once verified
            return true;
        }
        return false;
    }
}

