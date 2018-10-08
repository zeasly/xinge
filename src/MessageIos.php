<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/17
 * Time: 下午8:54
 */

namespace Zeasly\Xinge;


use Xinge\Kernel\Exceptions\InvalidArgumentException;

class MessageIos extends Message
{
    /**
     * @var array 包含标题和消息内容
     */
    protected $alert;

    /**
     * @var int App显示的角标数
     */
    protected $badge;

    /**
     * @var
     */
    protected $sound;

    /**
     * @var string 下拉消息时显示的操作标识
     */
    protected $category;

    /**
     * @var string ios 发送环境
     */
    protected $environment;

    function toPushData()
    {
        $re = parent::toPushData();

        $re['platform'] = MessageIos::PLATFORM_IOS;
        if (!is_null($this->environment)) {
            $re['environment'] = $this->environment;
        }

        return $re;
    }

    public function getMessageData()
    {
        if ($this->raw) {
            return $this->raw;
        }

        $ios = $this->custom;
        //根据不同类型,组装成不同的数据
        switch ($this->type) {
            case Message::TYPE_NOTIFICATION:
                if (!is_null($this->alert)) {
                    $ios['aps']['alert'] = $this->alert;
                }
                if (!is_null($this->badge)) {
                    $ios['aps']['badge_type'] = $this->badge;
                }
                if (!is_null($this->category)) {
                    $ios['aps']['category'] = $this->category;
                }
                if (!is_null($this->sound)) {
                    $ios['aps']['sound'] = $this->sound;
                }
                $re = [
                    'title'   => $this->title,
                    'content' => $this->content,
                    'ios'     => $ios,
                ];

                break;
            case Message::TYPE_MESSAGE:
                $ios['aps'] = [
                    'content-available' => 1,
                ];
                $re = [
                    'ios' => $ios,
                ];

                break;
            default:
                throw new InvalidArgumentException('错误的消息类型', 500);
                break;
        }
        $re['accept_time'] = $this->acceptTimes;

        return $re;
    }
}