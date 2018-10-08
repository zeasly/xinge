<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xinge\Push;

use BaseSdk\Kernel\BaseClient;
use Psr\Http\Message\RequestInterface;

/**
 * Class Client.
 *
 * @author overtrue <i@overtrue.me>
 */
class Client extends BaseClient
{
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

    public function registerHttpMiddlewares()
    {
        $this->pushMiddleware($this->authMiddleware(), 'auth');
        parent::registerHttpMiddlewares();
    }

    public function authMiddleware()
    {
        $appId = $this->app['config']->app_id;
        $secretKey = $this->app['config']->secret_key;
        return function (callable $handler) use ($appId, $secretKey) {
            return function (RequestInterface $request, array $options) use ($handler, $appId, $secretKey) {
                $request = $request->withHeader('Authorization', 'Basic ' . base64_encode($appId . ':' . $secretKey));
                return $handler($request, $options);
            };
        };
    }


    public function getPushParam(array $message)
    {
        if ($message['platform'] == 'ios' && !isset($message['environment'])) {
            $message['environment'] = $this->app['config']->environment;
        }

        return $message;
    }


    /**
     * 推送消息给APP所有设备
     */
    public function toAll(array $message)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Client::AUDIENCE_TYPE_ALL;

        return $this->push($param);
    }

    /**
     * 推送消息给多个账户
     */
    public function toToken($token, array $message)
    {
        if (is_array($token)) {
            return $this->toTokens($token, $message);
        }

        $param = $this->getPushParam($message);
        $param['audience_type'] = Client::AUDIENCE_TYPE_TOKEN;
        $param['token_list'] = [$token];

        return $this->push($param);
    }

    public function toTokens($tokenList, array $message)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Client::AUDIENCE_TYPE_TOKEN_LIST;
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
    public function toAccount($account, array $message)
    {
        if (is_array($account)) {
            return $this->toAccounts($account, $message);
        }
        $param = $this->getPushParam($message);
        $param['audience_type'] = Client::AUDIENCE_TYPE_ACCOUNT;
        $param['account_list'] = [$account];

        return $this->push($param);
    }

    public function toAccounts($accountList, array $message)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Client::AUDIENCE_TYPE_ACCOUNT_LIST;
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
    public function toTag($tagList, array $message, $op = Client::TAG_OP_AND)
    {
        $param = $this->getPushParam($message);
        $param['audience_type'] = Client::AUDIENCE_TYPE_TAG;
        $param['tag_list'] = [
            'tags' => $tagList,
            'op'   => $op,
        ];

        return $this->push($param);
    }

    public function push($param)
    {
        return $response = $this->httpPostJson(Client::API_PUSH, $param);
    }

}
