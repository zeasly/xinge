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
    'default' => [
        'app_id'      => env('XINGE_APP_ID'),
        'secret_key'  => env('XINGE_SECRET_KEY'),
        'access_id'   => env('XINGE_ACCESS_ID'),
        'environment' => env('XINGE_ENVIRONMENT', 'dev'),
    ],
];
