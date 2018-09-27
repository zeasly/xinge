<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/17
 * Time: 下午9:17
 */

namespace Xinge;


use Xinge\Kernel\Traits\HasHttpRequests;

class Push
{
    use HasHttpRequests;

    // v3 接口
    const API_PUSH = 'https://openapi.xg.qq.com/v3/push/app';

    //推送目标类型
    const AUDIENCE_TYPE_ALL = 'all';
    const AUDIENCE_TYPE_TAG = 'tag';
    const AUDIENCE_TYPE_TOKEN = 'token';
    const AUDIENCE_TYPE_TOKEN_LIST = 'token_list';
    const AUDIENCE_TYPE_ACCOUNT = 'account';
    const AUDIENCE_TYPE_ACCOUNT_LIST = 'account_list';

    const IOS_ENV_PROD = 'product';
    const IOS_ENV_DEV = 'dev';

    const TAG_OP_AND = 'AND';
    const TAG_OP_OR = 'OR';

    const IOS_MIN_ID = 2200000000;

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

    public function getPushParam(Message $message)
    {
        $param = $message->getPushData();
        if ($message instanceof MessageIos && !isset($param['environment'])) {
            $param['environment'] = $this->environment;
        }

        return $param;
    }


    /**
     * 推送消息给APP所有设备
     */
    public function toAll(Message $message)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Push::AUDIENCE_TYPE_ALL;

        return $this->push($param);
    }

    /**
     * 推送消息给多个账户
     */
    public function toToken($token, Message $message)
    {
        if (is_array($token)) {
            return $this->toTokens($token, $message);
        }

        $param = $this->getPushParam($message);
        $param['audience_type'] = Push::AUDIENCE_TYPE_TOKEN;
        $param['token_list'] = [$token];

        return $this->push($param);
    }

    public function toTokens($tokenList, Message $message)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Push::AUDIENCE_TYPE_TOKEN_LIST;
        $param['push_id'] = 0;

        //每次只能发送1000个
        $list = array_chunk($tokenList, 1000);
        //第一次单独处理
        $param['token_list'] = $list[0];
        $first = $this->push($param);

        //第一次发送成功就继续处理后面的
        if ($first) {
            unset($list[0]);
            foreach ($list as $v) {
                $param['token_list'] = $v;
                $param['push_id'] = $first['push_id'];
                $this->push($param);
            }
        }

        return $first;
    }


    /**
     * 推送消息给单个设备
     */
    public function toAccount($account, Message $message)
    {
        if (is_array($account)) {
            return $this->toAccounts($account, $message);
        }
        $param = $this->getPushParam($message);
        $param['audience_type'] = Push::AUDIENCE_TYPE_ACCOUNT;
        $param['account_list'] = [$account];

        return $this->push($param);
    }

    public function toAccounts($accountList, Message $message)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Push::AUDIENCE_TYPE_ACCOUNT_LIST;
        $param['push_id'] = 0;

        //每次只能发送1000个
        $list = array_chunk($accountList, 1000);
        //第一次单独处理
        $param['account_list'] = $list[0];
        $first = $this->push($param);

        //第一次发送成功就继续处理后面的
        if ($first) {
            unset($list[0]);
            foreach ($list as $v) {
                $param['account_list'] = $v;
                $param['push_id'] = $first['push_id'];
                $this->push($param);
            }
        }

        return $first;
    }


    /**
     * 推送消息给指定tags的设备
     * 若要推送的tagList只有一项，则tagsOp应为OR
     */
    public function toTag($tagList, Message $message, $op = Push::TAG_OP_AND)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Push::AUDIENCE_TYPE_TAG;
        $param['tag_list'] = [
            'tags' => $tagList,
            'op'   => $op,
        ];

        return $this->push($param);
    }

    public function push($param)
    {
        $header = [
            'Authorization' => 'Basic ' . base64_encode($this->appId . ':' . $this->secretKey),
        ];
        $response = $this->postJson(Push::API_PUSH, $param, $header);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getPushStatus($pushId)
    {

    }


}