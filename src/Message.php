<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/17
 * Time: 下午8:54
 */

namespace Xinge;


abstract class Message
{
    const TYPE_NOTIFICATION = 'notify';
    const TYPE_MESSAGE = 'message';
    const MAX_LOOP_TASK_DAYS = 15;

    protected $pusher;

    # 通用
    protected $title;
    protected $content;
    protected $expireTime;
    protected $sendTime;
    protected $acceptTimes;
    protected $type;
    protected $custom;
    protected $raw;
    protected $loopInterval;
    protected $loopTimes;

    # android
    protected $multiPkg;
    protected $style;
    protected $action;


    public function __construct()
    {

    }

    /**
     * @author xiaoze <zeasly@live.com>
     * @return array
     */
    abstract function toArray();

    /**
     * @author xiaoze <zeasly@live.com>
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}