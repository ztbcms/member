<?php
/**
 * Author: jayinton
 */

namespace app\member\service;


use app\common\service\BaseService;
use app\member\model\MemberModel;
use app\member\model\MemberRoleModel;
use think\exception\InvalidArgumentException;

// 会员角色
class MemberRoleService extends BaseService
{
    /**
     * 添加/编辑角色
     *
     * @param $roleData
     *
     * @return array
     */
    function addOrEditRole($roleData)
    {
        $data = [
            'name'   => $roleData['name'],
            'remark' => $roleData['remark'],
            'status' => $roleData['status'],
        ];

        $roleModel = new MemberRoleModel();
        if (isset($roleData['id']) && !empty($roleData['id'])) {
            // 编辑
            $data['update_time'] = time();
            $res = $roleModel->where('id', $roleData['id'])->save($data);
        } else {
            // 新增
            $data['create_time'] = $data['update_time'] = time();
            $res = $roleModel->insert($data);
        }
        if (!$res) {
            return self::createReturn(false, null, '操作失败');
        }
        return self::createReturn(true, null, '操作成功');
    }

    /**
     * 删除角色
     *
     * @param $role_id
     *
     * @return array
     */
    function deleteRole($role_id)
    {
        if (empty($role_id)) {
            throw new InvalidArgumentException('请指定角色');
        }
        //角色信息
        $roleModel = new MemberRoleModel();
        $info = $roleModel->where('id', $role_id)->find();
        if (empty($info) || !isset($info)) {
            throw new InvalidArgumentException('该角色不存在');
        }
        $memberModel = new MemberModel();
        $member = $memberModel->where('role_id', $role_id)->find();
        if ($member) {
            return self::createReturn(false, null, '该角色下有成员，无法删除');
        }
        $res = $roleModel->where('id', $role_id)->delete();
        if ($res) {
            return self::createReturn(true, null, '操作成功');
        }
        return self::createReturn(false, null, '操作失败');
    }

}