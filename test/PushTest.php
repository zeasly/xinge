<?php

use PHPUnit\Framework\TestCase;
use Xinge\MessageIos;

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
            'app_id'      => 'da170e0e9cd0f',
            'secret_key'  => '29704b21362f237febab81d571609f18',
            'access_id'   => '2200311707',
            'environment' => \Xinge\Push\Client::IOS_ENV_DEV,

        ];
        $app = new \Xinge\Application($config);

        $re = $app->push->toToken('12332', new MessageIos('test', 'test'));
        var_dump($re);exit;

    }

}
