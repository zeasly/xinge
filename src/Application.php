<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xinge;

use BaseSdk\Kernel\ServiceContainer;
use Xinge\Push\Client;

/**
 * Class Application.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property Client $push
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Push\ServiceProvider::class,
    ];
}
