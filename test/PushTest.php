<?php

use PHPUnit\Framework\TestCase;
use Xinge\MessageIos;
use Xinge\Push;

/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/18
 * Time: 下午5:23
 */
class PushTest extends TestCase
{
    public function testAbs()
    {
        $config = [
            'app_id'      => 'aa',
            'secret_key'  => 'bb',
            'access_id'   => 'cc',
            'environment' => Push::IOS_ENV_DEV,

        ];
        $push = new Push($config);
        $this->assertEquals(4, $push->toAll(new MessageIos('test', 'test')));

    }

}
