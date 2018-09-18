<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/9/18
 * Time: 上午11:15
 */

namespace Xinge;


class Action
{
    const TYPE_ACTIVITY = 1;
    const TYPE_URL = 2;
    const TYPE_INTENT = 3;

    private $actionType = Action::TYPE_ACTIVITY;
    private $url;
    private $confirmOnUrl;
    private $activity;
    private $intent;
    private $atyAttrIntentFlag = 0;
    private $atyAttrPendingIntentFlag = 0;
    private $packageDownloadUrl;
    private $confirmOnPackageDownloadUrl = 0;
    private $packageName;


    public function toMessageData()
    {
        $re = [
            'action_type' => $this->actionType,
            'browser'     => [
                'url'     => $this->url,
                'confirm' => $this->confirmOnUrl,
            ],
            'activity'    => $this->activity,
            'intent'      => $this->intent,
            'aty_attr'    => [
                'if' => $this->atyAttrIntentFlag,
                'pf' => $this->atyAttrPendingIntentFlag,
            ],
        ];

        return $re;
    }

    public function isValid()
    {
        if (!isset($this->actionType)) {
            $this->actionType = self::TYPE_ACTIVITY;
        }

        if (!is_int($this->actionType)) {
            return false;
        }

        if ($this->actionType < self::TYPE_ACTIVITY || $this->actionType > self::TYPE_INTENT) {
            return false;
        }

        if ($this->actionType == self::TYPE_ACTIVITY) {
            if (!isset($this->activity)) {
                $this->activity = "";
                return true;
            }
            if (isset($this->atyAttrIntentFlag)) {
                if (!is_int($this->atyAttrIntentFlag)) {
                    return false;
                }
            }
            if (isset($this->atyAttrPendingIntentFlag)) {
                if (!is_int($this->atyAttrPendingIntentFlag)) {
                    return false;
                }
            }

            if (is_string($this->activity) && !empty($this->activity)) {
                return true;
            }

            return false;
        }

        if ($this->actionType == self::TYPE_URL) {
            if (is_string($this->url) && !empty($this->url) &&
                is_int($this->confirmOnUrl) &&
                $this->confirmOnUrl >= 0 && $this->confirmOnUrl <= 1
            ) {
                return true;
            }

            return false;
        }

        if ($this->actionType == self::TYPE_INTENT) {
            if (is_string($this->intent) && !empty($this->intent)) {
                return true;
            }

            return false;
        }
    }
}
