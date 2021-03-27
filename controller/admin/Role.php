<?php
/**
 * Author: jayinton
 */

namespace app\member\controller\admin;


use app\common\controller\AdminController;
use app\member\model\MemberRoleModel;
use app\member\service\MemberRoleService;
use think\facade\Request;

class Role extends AdminController
{
    /**
     * 角色列表
     */
    function index()
    {
        $action = input('_action', '', 'trim');
        if (Request::isGet() && $action == 'getList') {

            return $this->getRoleList();
        }
        return view('index');
    }

    /**
     * 新增角色
     *
     */
    function addRole()
    {
        if (Request::isPost()) {
            return $this->addEditRole();
        }

        return view('addOrEditRole');
    }

    /**
     * 编辑角色
     *
     */
    function editRole()
    {
        if (Request::isPost()) {
            return $this->addEditRole();
        }

        $id = Request::param('id', '', 'trim');
        $action = input('_action', '', 'trim');

        if (Request::isGet() && $action == 'getDetail') {

            $RoleModel = new MemberRoleModel();
            $info = $RoleModel->where(['id' => $id])->find();
            return self::makeJsonReturn(true, $info);
        }
        return view('addOrEditRole');
    }

    /**
     * 获取角色列表
     *
     * 如果是超级管理员，显示所有角色。如果非超级管理员，只显示下级角色
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function getRoleList()
    {
        $status = input('status', null);
        $where = [];
        if ($status !== null) {
            $where [] = ['status', '=', $status];
        }
        $RoleModel = new MemberRoleModel();
        $list = $RoleModel->where($where)->select()->toArray();
        return json(self::createReturn(true, $list));
    }

    /**
     * 添加或者编辑角色
     */
    private function addEditRole()
    {
        $id = Request::param('id', '', 'trim');
        $name = Request::param('name', '', 'trim');
        $remark = Request::param('remark', '', 'trim');
        $status = Request::param('status', '1', 'trim');
        if (empty($name)) {
            return json(self::createReturn(false, null, '请填写角色名称'));
        }
        $data['id'] = $id;
        $data['name'] = $name;
        $data['remark'] = $remark;
        $data['status'] = $status;
        $roleService = new MemberRoleService();
        $res = $roleService->addOrEditRole($data);
        return json($res);
    }

    /**
     * 删除角色
     */
    function deleteRole()
    {
        $id = Request::param('id', '', 'trim');
        $roleService = new MemberRoleService();
        $res = $roleService->deleteRole($id);
        return json($res);
    }
}