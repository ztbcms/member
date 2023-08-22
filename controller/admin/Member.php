<?php
/**
 * Author: jayinton
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberModel;
use app\member\model\MemberRoleModel;
use app\member\service\MemberService;
use think\Request;

/**
 * 会员管理
 * Class Member
 * @package app\member\controller\admin
 */
class Member extends AdminController
{
    /**
     * 会员列表
     *
     * @param  Request  $request
     *
     * @return \think\response\Json|\think\response\View
     */
    function index(Request $request)
    {
        $action = $request->get('_action');
        if ($request->isGet() && $action == 'getList') {
            return $this->getList();
        } elseif ($request->isGet() && $request->param('_action') == 'getRoleList') {
            return $this->getRoleList();
        } elseif ($request->isPost() && $request->param('_action') == 'blockMember') {
            return $this->blockMember();
        } elseif ($request->isPost() && $request->param('_action') == 'batchBlockMember') {
            return $this->batchBlockMember();
        } elseif ($request->isPost() && $request->param('_action') == 'auditMember') {
            return $this->auditMember();
        } elseif ($request->isPost() && $request->param('_action') == 'batchAuditMember') {
            return $this->batchAuditMember();
        }
        return view();
    }

    // 获取列表
    private function getList()
    {
        $where = [];
        $user_id = input('user_id');
        if ($user_id) {
            $where[] = ['user_id', '=', $user_id];
        }

        $username = input('username');
        if ($username) {
            $where[] = ['username', 'like', $username];
        }

        $phone = input('phone');
        if ($phone) {
            $where[] = ['phone', 'like', $phone];
        }

        $email = input('email');
        if ($email) {
            $where[] = ['email', 'like', $email];
        }

        $role_id = input('role_id');
        if ($role_id) {
            $where[] = ['role_id', '=', $role_id];
        }

        $tab = input('tab');
        if ($tab == 1) {
            $where[] = ['audit_status', '=', 0];
        } else {
            if ($tab) {
                $where[] = ['audit_status', '=', 2];
            } else {
                if ($tab) {
                    $where[] = ['is_block', '=', 1];
                }
            }
        }

        $MemberModel = new MemberModel();
        $list = $MemberModel
            ->where($where)
            ->with(['role_info', 'grade_info'])
            ->order('reg_time desc')
            ->paginate(input('limit'));
        return json(self::createReturn(true, $list));
    }

    /**
     * 拉黑、启用用户
     *
     * @return \think\response\Json
     */
    private function blockMember()
    {
        return json(MemberService::blockMember(input('user_id'), input('is_block')));
    }

    /**
     * 批量 拉黑、启用用户
     *
     * @return \think\response\Json
     */
    private function batchBlockMember()
    {
        $user_ids = input('user_ids', []);
        foreach ($user_ids as $userId) {
            MemberService::blockMember($userId, input('is_block', 0));
        }
        return self::makeJsonReturn(true, [], '操作成功');
    }

    /**
     * 审核会员
     *
     * @return \think\response\Json|
     */
    private function auditMember()
    {
        $user_id = input('user_id');
        if (empty($user_id)) {
            return self::makeJsonReturn(false, '', '请选择会员');
        }
        MemberService::auditMember($user_id, input('audit_status'));
        return self::makeJsonReturn(true, [], '操作成功');
    }

    /**
     * 批量审核会员
     *
     * @return \think\response\Json|
     */
    private function batchAuditMember()
    {
        $user_ids = $this->request->post('user_ids', []);
        $audit_status = $this->request->post('audit_status', 0);
        if (empty($user_ids)) {
            return self::makeJsonReturn(false, null, '请选择会员');
        }
        $total = 0;
        foreach ($user_ids as $userId) {
            $res = MemberService::auditMember($userId, $audit_status);
            if ($res['status']) {
                $total++;
            }
        }
        if ($total == count($user_ids)) {
            return self::makeJsonReturn(true, null, '操作成功');
        } else {
            return self::makeJsonReturn(false, null, '操作失败');
        }
    }

    // 添加会员
    function addMember()
    {
        if ($this->request->isGet() && $this->request->param('_action') === 'getRoleList') {
            return $this->getRoleList();
        }

        if ($this->request->isPost() && $this->request->param('_action') === 'addMember') {
            $post = input('post.');
            $res = MemberService::addOrEditMember($post);
            return json($res);
        }

        return view('addOrEditMember');
    }

    // 编辑会员
    function editMember()
    {
        if ($this->request->isGet() && $this->request->param('_action') === 'getRoleList') {
            return $this->getRoleList();
        }

        // 编辑
        if ($this->request->isPost() && $this->request->param('_action') === 'editMember') {
            $post = input('post.');
            $res = MemberService::addOrEditMember($post);
            return json($res);
        }

        // 详情
        if ($this->request->isGet() && $this->request->param('_action') === 'getDetail') {
            $user_id = input('user_id', '0', 'trim');
            $memberModel = new MemberModel();
            $res = $memberModel
                ->where('user_id', $user_id)
                ->withoutField('reg_ip,reg_time,update_time,encrypt,password')
                ->findOrEmpty();
            return self::makeJsonReturn(true, $res);
        }
        return view('addOrEditMember');
    }


    /**
     * 获取角色列表
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getRoleList()
    {
        $RoleModel = new MemberRoleModel();
        $list = $RoleModel
            ->where('status', MemberRoleModel::STATUS_YES)
            ->select();
        return json(self::createReturn(true, $list));
    }


}