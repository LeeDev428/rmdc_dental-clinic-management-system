<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\UserProvider;

class RedisPasswordBroker extends PasswordBroker
{
    public function __construct(TokenRepositoryInterface $tokens, UserProvider $users)
    {
        parent::__construct($tokens, $users);
    }
}