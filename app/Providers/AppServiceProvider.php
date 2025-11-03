<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use App\Auth\RedisTokenRepository;
use App\Auth\RedisPasswordBroker;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Redis password broker
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            $config = $app['config']['auth.passwords.users'];
            
            $tokenRepository = new RedisTokenRepository(
                $app['hash'],
                $app['config']['app.key'],
                $config['expire']
            );
            
            $userProvider = Auth::createUserProvider($config['provider']);
            
            return new RedisPasswordBroker($tokenRepository, $userProvider);
        });
    }
}
