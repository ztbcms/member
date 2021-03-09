<?php
/**
 * Author: jayinton
 */

namespace app\member\service;


use app\common\service\BaseService;
use app\member\model\MemberModel;

class MemberService extends BaseService
{
    // 添加、更新会员
    function addOrEditMember()
    {

    }

    // 审核会员
    function auditMember()
    {

    }

    // 拉黑
    static function blockMember($user_id, $is_block)
    {
        $count = MemberModel::where('user_id', $user_id)->save(['is_block' => $is_block]);
        if ($count > 0) {
            return self::createReturn(true, null, '操作成功');
        }
        return self::createReturn(false, null, '操作失败');
    }

    // 取消拉黑
    function unblockMember()
    {
    }

    // 密码重置
    function resetMemberPassword()
    {
    }
}