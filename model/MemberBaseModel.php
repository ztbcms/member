<?php
/**
 * Author: cycle_3
 */

namespace app\member\model;

use think\Model;

class MemberBaseModel extends Model
{

    public $ztb_message = '';

    /**
     * @return string
     */
    public function getZtbMessage()
    {
        return $this->ztb_message;
    }

    /**
     * @param string $ztb_message
     */
    public function setZtbMessage($ztb_message)
    {
        $this->ztb_message = $ztb_message;
    }
}