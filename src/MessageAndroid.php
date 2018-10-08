<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/17
 * Time: 下午8:54
 */

namespace Zeasly\Xinge;


use Xinge\Kernel\Exceptions\InvalidArgumentException;

class MessageAndroid extends Message
{
    protected $multiPkg;

    protected $action;
    protected $builderId;
    protected $ring;
    protected $ringRaw;
    protected $vibrate;
    protected $clearable;
    protected $nId;
    protected $lights;
    protected $iconType;
    protected $iconRes;
    protected $styleId;
    protected $smallIcon;


    function toPushData()
    {
        $re = parent::toPushData();

        $re['platform'] = MessageAndroid::PLATFORM_ANDROID;
        $re['multi_pkg'] = $this->multiPkg;

        return $re;
    }

    public function getMessageData()
    {
        if ($this->raw) {
            return $this->raw;
        }

        $re = [
            'title'       => $this->title,
            'content'     => $this->content,
            'accept_time' => $this->acceptTimes,
        ];

        //根据不同类型,组装成不同的数据
        switch ($this->type) {
            case Message::TYPE_NOTIFICATION:
                $android = [
                    'builder_id' => $this->builderId,
                    'ring'       => $this->ring,
                    'vibrate'    => $this->vibrate,
                    'clearable'  => $this->clearable,
                    'n_id'       => $this->nId,
                    'lights'     => $this->lights,
                    'icon_type'  => $this->iconType,
                    'style_id'   => $this->styleId,
                ];

                if (!is_null($this->ringRaw)) {
                    $ret_android['ring_raw'] = $this->ringRaw;
                }
                if (!is_null($this->iconRes)) {
                    $ret_android['icon_res'] = $this->iconRes;
                }
                if (!is_null($this->smallIcon)) {
                    $ret_android['small_icon'] = $this->smallIcon;
                }

                if ($this->action) {
                    $android['action'] = $this->action->toMessageData();
                }

                $re['android'] = $android;

                break;
            case Message::TYPE_MESSAGE:
                //什么也不做,只是防止type 验证不通过
                break;
            default:
                throw new InvalidArgumentException('错误的消息类型', 500);
                break;
        }
        $ret['android']['custom_content'] = $this->custom;

        return $re;
    }

}