<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class RedisTokenRepository extends DatabaseTokenRepository
{
    protected $redis;
    protected $hashKey;
    protected $expires;

    public function __construct(Hasher $hasher, $hashKey, $expires = 60)
    {
        $this->hasher = $hasher;
        $this->hashKey = $hashKey;
        $this->expires = $expires * 60; // Convert to seconds
        $this->redis = Redis::connection();
    }

    public function create($user)
    {
        $email = $user->getEmailForPasswordReset();
        
        $this->deleteExisting($user);
        
        $token = $this->createNewToken();
        
        $this->redis->setex(
            $this->getRedisKey($email),
            $this->expires,
            json_encode([
                'email' => $email,
                'token' => $this->hasher->make($token),
                'created_at' => time()
            ])
        );
        
        return $token;
    }

    public function exists($user, $token)
    {
        $record = $this->getRecord($user);
        
        return $record && 
               !$this->tokenExpired($record['created_at']) &&
               $this->hasher->check($token, $record['token']);
    }

    public function delete($user)
    {
        $this->deleteExisting($user);
    }

    public function deleteExpired()
    {
        // Redis handles expiration automatically
    }

    protected function deleteExisting($user)
    {
        $this->redis->del($this->getRedisKey($user->getEmailForPasswordReset()));
    }

    protected function getRecord($user)
    {
        $email = $user->getEmailForPasswordReset();
        $data = $this->redis->get($this->getRedisKey($email));
        
        return $data ? json_decode($data, true) : null;
    }

    protected function getRedisKey($email)
    {
        return 'password_reset:' . $email;
    }

    public function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), $this->hashKey);
    }

    protected function tokenExpired($createdAt)
    {
        return (time() - $createdAt) >= $this->expires;
    }
}