<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/17
 * Time: 下午9:17
 */

namespace Xinge;


class Push
{
    // v3 接口
    const RESTAPI_PUSH = 'https://openapi.xg.qq.com/v3/push/app';
    const RESTAPI_QUERYPUSHSTATUS = 'http://openapi.xg.qq.com/v2/push/get_msg_status';
    const RESTAPI_CANCELTIMINGPUSH = 'http://openapi.xg.qq.com/v2/push/cancel_timing_task';

    const DEVICE_ALL = 0;
    const DEVICE_BROWSER = 1;
    const DEVICE_PC = 2;
    const DEVICE_ANDROID = 3;
    const DEVICE_IOS = 4;
    const DEVICE_WINPHONE = 5;

    const IOSENV_PROD = 'product';
    const IOSENV_DEV = 'dev';

    const IOS_MIN_ID = 2200000000;

    public $appId = '';
    public $secretKey = '';
    public $accessId = '';
    public $environment = Push::IOSENV_DEV;

    public function __construct(array $config)
    {
        if (!isset($config['app_id']) || !isset($config['secret_key'])) {
            throw new Exception('错误的配置文件', -1);
        }

        $this->appId = $config['app_id'];
        $this->secretKey = $config['secret_key'];
        $this->accessId = $config['access_id'] ?? '';
        if (isset($config['environment'])) {
            $this->environment = $config['environment'];
        }
    }


}