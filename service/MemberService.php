<?php
/**
 * Author: jayinton
 */

namespace app\member\service;


use app\common\service\BaseService;

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
    function blockMember($user_id, $is_block)
    {


        $userIds = $this->request->post('user_id', 0);
        $isBlock = $this->request->post('is_block', 0);
        if (empty($userIds)) {
            return self::makeJsonReturn(false, '', '请选择');
        }
        $res = MemberUserService::blockUser($userIds, $isBlock);
        if ($res) {
            return self::makeJsonReturn(true, '', '操作成功');
        }
        return self::makeJsonReturn(false, '', '操作失败');
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