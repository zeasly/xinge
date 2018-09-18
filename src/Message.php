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

    /**
     * @var Push 发送服务
     */
    protected $pusher;

    # 通用
    /**
     * @var string 标题
     */
    protected $title;

    /**
     * @var string 详情
     */
    protected $content;

    /**
     * @var string 消息离线存储时间
     */
    protected $expireTime;

    /**
     * @var string 指定推送时间
     */
    protected $sendTime;

    /**
     * @var array 消息将在哪些时间段允许推送给用户
     */
    protected $acceptTimes;

    /**
     * @var string 消息类型
     */
    protected $type;

    /**
     * @var array 自定义数据
     */
    protected $custom;

    /**
     * @var array 指定消息数据
     */
    protected $raw;

    /**
     * @var int 循环执行消息下发的间隔
     */
    protected $loopInterval;

    /**
     * @var int 循环任务重复次数
     */
    protected $loopTimes;

    /**
     * @var string 统计标签，用于聚合统计
     */
    protected $statTag;


    public function __construct()
    {

    }


    function getPushData()
    {
        $re = [
            'message_type' => $this->type,
            'message'      => $this->getMessageData(),
        ];

        if (!is_null($this->expireTime)) {
            $re['expire_time'] = $this->expireTime;
        }
        if (!is_null($this->sendTime)) {
            $re['send_time'] = $this->sendTime;
        }
        if (!is_null($this->loopInterval)) {
            $re['loop_interval'] = $this->loopInterval;
        }
        if (!is_null($this->loopTimes)) {
            $re['loop_times'] = $this->sendTime;
        }

        return $re;
    }

    abstract public function getMessageData();

    public function __set($key, $value)
    {
        if (method_exists($this, 'set' . $key)) {
            $method = 'set' . $key;
            return $this->$method($key);
        }
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }

        return $this;
    }

    public function getPusher($config = null)
    {
        if ($config) {
            $this->pusher = '';
        }
    }
}