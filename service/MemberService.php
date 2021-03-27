<?php
/**
 * Author: jayinton
 */

namespace app\member\service;


use app\common\libs\helper\PasswordHelper;
use app\common\libs\helper\StringHelper;
use app\common\service\BaseService;
use app\member\model\MemberModel;
use think\facade\Request;
use think\facade\Validate;

class MemberService extends BaseService
{
    // 添加、更新会员
    static function addOrEditMember($user_data)
    {
        if (empty($user_data) || !is_array($user_data)) {
            return self::createReturn(false, null, '参数异常');
        }
        $data = [];
        isset($user_data['username']) && $data['username'] = $user_data['username'];
        isset($user_data['password']) && $data['password'] = $user_data['password'];
        isset($user_data['email']) && $data['email'] = $user_data['email'];
        isset($user_data['nickname']) && $data['nickname'] = $user_data['nickname'];
        isset($user_data['remark']) && $data['remark'] = $user_data['remark'];
        isset($user_data['role_id']) && $data['role_id'] = $user_data['role_id'];
        if(isset($user_data['audit_status'])){
            $data['audit_status'] = $user_data['audit_status'];
        } else {
            $data['audit_status'] = MemberModel::AUDIT_STATUS_PASS;
        }
        if(isset($user_data['is_block'])){
            $data['is_block'] = $user_data['is_block'];
        } else {
            $data['is_block'] = MemberModel::IS_BLOCK_NO;
        }
        $data['update_time'] = time();
        $memberModel = new MemberModel();
        $user_id = $user_data['id'] ?? null;
        // 校验用户名/邮箱
        $check_username = null;
        if (isset($data['username'])) {
            $check_username = $memberModel->where('username', $data['username'])->find();
        }
        if (isset($data['email'])) {
            $validate = Validate::rule('email', 'email');
            if(!$validate->check($data)){
                return self::createReturn(false, null, '邮箱格式错误');
            }
        }
        if (isset($data['password']) && !empty($data['password'])) {
            $encrypt = StringHelper::genRandomString(6);
            $data['encrypt'] = $encrypt;
            $data['password'] = PasswordHelper::hashPassword($data['password'], $encrypt);
        } else {
            unset($data['password']);
        }
        if (isset($data['nickname']) && empty($data['nickname'])) {
            return self::createReturn(false, null, '昵称不能为空');
        }
        if (!empty($user_id)) {
            // 编辑
            if (isset($data['username'])) {
                if ($check_username && $check_username['id'] != $user_id) {
                    return self::createReturn(false, null, '用户名已存在');
                }
            }
            $res = $memberModel->where('id', $user_id)->save($data);
            if ($res) {
                return self::createReturn(true, null, '更新成功');
            }
        } else {
            // 新增
            if ($check_username) {
                return self::createReturn(false, null, '用户名已存在');
            }
            if (!isset($data['role_id']) || empty($data['role_id'])){
                return self::createReturn(false, null, '请选择角色');
            }
            $data['reg_time'] = time();
            $data['reg_ip'] = Request::ip();
            $res = $memberModel->insert($data);
            if ($res) {
                return self::createReturn(true, null, '操作成功');
            }
        }
        return self::createReturn(true, null, '操作失败');
    }

    // 审核
    static function auditMember($user_id, $audit_status)
    {
        $count = MemberModel::where('user_id', $user_id)->save(['audit_status' => $audit_status, 'update_time' => time()]);
        if ($count > 0) {
            return self::createReturn(true, null, '操作成功');
        }
        return self::createReturn(false, null, '操作失败');
    }

    // 拉黑
    static function blockMember($user_id, $is_block)
    {
        $count = MemberModel::where('user_id', $user_id)->save(['is_block' => $is_block, 'update_time' => time()]);
        if ($count > 0) {
            return self::createReturn(true, null, '操作成功');
        }
        return self::createReturn(false, null, '操作失败');
    }


    // 密码重置
    function resetMemberPassword()
    {
    }
}