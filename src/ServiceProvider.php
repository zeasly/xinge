<?php

/*
 * This file is part of the overtrue/laravel-wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\LaravelWeChat;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Zeasly\Xinge\Application;

/**
 * Class ServiceProvider.
 *
 * @author overtrue <i@overtrue.me>
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     */
    public function boot()
    {
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/config.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('xinge.php')], 'laravel-wechat');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('xinge');
        }

        $this->mergeConfigFrom($source, 'xinge');
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->setupConfig();
        $accounts = config('xinge.accounts');
        foreach ($accounts as $account => $config) {
            $this->app->singleton("xinge.{$account}",
                function ($laravelApp) use ($account, $config) {
                    $app = new Application($config);
                    $app['request'] = $laravelApp['request'];

                    return $app;
                });
        }
        $this->app->alias("xinge.default", 'xinge');
    }

}
