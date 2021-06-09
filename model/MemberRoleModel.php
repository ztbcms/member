<?php
/**
 * Author: jayinton
 */

namespace app\member\model;


use think\Model;

class MemberRoleModel extends Model
{
    protected $name = 'member_role';

    // 启用状态
    /**
     * 启用状态：启用
     */
    const STATUS_YES = 1;
    /**
     * 启用状态：禁用
     */
    const STATUS_NO = 0;

    /**
     * 获取启用的角色列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function getEnableRoleList()
    {
        $RoleModel = new MemberRoleModel();
        return $RoleModel->where('status', MemberRoleModel::STATUS_YES)->select()->toArray() ?: [];
    }
}