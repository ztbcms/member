<?php
/**
 * Author: jayinton
 */

namespace app\member\model;


use think\Model;

class MemberModel extends Model
{
    protected $name = 'member';
    protected $pk = 'user_id';


    // 拉黑
    const IS_BLOCK_YES = 1;
    // 未拉黑
    const IS_BLOCK_NO = 0;

    // 待审核
    const AUDIT_STATUS_WIATING = 0;
    // 审核通过
    const AUDIT_STATUS_PASS = 1;
    // 审核不通过
    const AUDIT_STATUS_UNPASS = 2;

}