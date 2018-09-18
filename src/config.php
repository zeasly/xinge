<?php

/*
 * This file is part of the overtrue/laravel-wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'ios' => [
        'default' => [
            'app_id'      => env('XINGE_IOS_APP_ID'),
            'secret_key'  => env('XINGE_IOS_SECRET_KEY'),
            'access_id'   => env('XINGE_IOS_ACCESS_ID'),
            'environment' => env('XINGE_IOS_ENVIRONMENT', 'dev'),
        ],
    ],

    'android' => [
        'default' => [
            'app_id'     => env('XINGE_ANDROID_APP_ID'),
            'secret_key' => env('XINGE_ANDROID_SECRET_KEY'),
            'access_id'  => env('XINGE_ANDROID_ACCESS_ID'),
        ],
    ],

];
