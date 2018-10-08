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

use Illuminate\Support\Facades\Facade as LaravelFacade;
use Zeasly\Xinge\Application;

/**
 * Class Facade.
 *
 * @author overtrue <i@overtrue.me>
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'xinge';
    }

    /**
     * @return Application
     */
    public static function account($name = '')
    {
        return $name ? app('xinge.' . $name) : app('xinge');
    }

}
