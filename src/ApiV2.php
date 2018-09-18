<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/18
 * Time: 上午11:15
 */

namespace Xinge;

use Xinge\Kernel\Traits\HasHttpRequests;

class ApiV2
{
    use HasHttpRequests;

    const API_QUERY_PUSH_STATUS = 'http://openapi.xg.qq.com/v2/push/get_msg_status';
    const API_CANCEL_TIMING_PUSH = 'http://openapi.xg.qq.com/v2/push/cancel_timing_task';

    public $appId = '';
    public $secretKey = '';
    public $accessId = '';
    public $environment = Push::IOS_ENV_DEV;

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


    /**
     *检查推送状态
     * @param  array|string $pushIds
     * @author xiaoze <zeasly@live.com>
     * @return mixed
     */
    public function getPushStatus($pushIds)
    {
        if (is_array($pushIds)) {
            foreach ($pushIds as &$v) {
                $v = ['push_id' => $v];
            }
            unset($v);
            $param['push_ids'] = json_encode($pushIds);
        } else {
            $param['push_ids'] = json_encode([['push_id' => $pushIds]]);
        }

        return $this->callApi(ApiV2::API_QUERY_PUSH_STATUS, $param);
    }

    /**
     * 取消推送
     * @param $pushId
     * @author xiaoze <zeasly@live.com>
     * @return mixed
     */
    public function cancelPush($pushId)
    {
        return $this->callApi(ApiV2::API_CANCEL_TIMING_PUSH, ['push_id' => $pushId]);
    }

    /**
     * 带基础参数的请求接口
     * @param $url
     * @param array $param
     * @param string $method
     * @author xiaoze <zeasly@live.com>
     * @return mixed
     */
    public function callApi($url, $param = [], $method = 'GET')
    {
        $param = array_replace_recursive($param, $this->baseParams());
        $param['sign'] = $this->getSign($url, $param, $method);

        if (strtolower($method) == 'get') {
            $response = $this->get($url, $param);
        } else {
            $response = $this->post($url, $param, ['Content-type' => 'application/x-www-form-urlencoded']);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 获取签名
     * @param $method
     * @param $url
     * @param array $params
     * @author xiaoze <zeasly@live.com>
     * @return string
     */
    public function getSign($url, $params = [], $method = 'GET')
    {
        //将参数进行升序排序
        $method = strtoupper($method);
        $urlInfo = parse_url($url);
        if (isset($urlInfo['host']) && isset($urlInfo['path'])) {
            $url = $urlInfo['host'] . $urlInfo['path'];
        }
        unset($params['sign']);
        ksort($params);
        foreach ($params as $k => &$v) {
            $v = $k . '=' . $v;
        }
        unset($v);

        return md5($method . $url . join('', $params) . $this->secretKey);
    }

    public function baseParams()
    {
        return [
            'access_id' => $this->accessId,
            'timestamp' => time(),
        ];
    }
}
