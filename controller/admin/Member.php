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
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 15);
        $param = $this->request->param();
        $where = [];
        if (isset($param['user_id']) && !empty($param['user_id'])) {
            $where[] = ['user_id', '=', $param['user_id']];
        }
        if (isset($param['username']) && !empty($param['username'])) {
            $where[] = ['username', '=', $param['username']];
        }
        if (isset($param['phone']) && !empty($param['phone'])) {
            $where[] = ['phone', '=', $param['phone']];
        }
        if (isset($param['email']) && !empty($param['email'])) {
            $where[] = ['email', '=', $param['email']];
        }
        if (isset($param['role_id']) && !empty($param['role_id'])) {
            $where[] = ['role_id', '=', $param['role_id']];
        }
        if (isset($param['tab']) && !empty($param['tab'])) {
            switch ($param['tab']) {
                case 1:
                    $where[] = ['audit_status', '=', 0];
                    break;
                case 2:
                    $where[] = ['audit_status', '=', 2];
                    break;
                case 3:
                    $where[] = ['is_block', '=', 1];
                    break;
            }
        }

        $roleModel = new MemberRoleModel();
        $roleList = $roleModel->getEnableRoleList();
        $roleMap = [];
        foreach ($roleList as $item){
            $roleMap[$item['id']] = $item;
        }
        $data = MemberModel::where($where)
            ->order('reg_time', 'desc')
            ->page($page)->limit($limit)->select()->toArray();
        $list = [];
        foreach ($data as $item){
            $list [] = [
                'user_id'           => $item['user_id'],
                'avatar'       => $item['avatar'],
                'username'     => $item['username'],
                'phone'        => $item['phone'],
                'email'        => $item['email'],
                'reg_ip'       => $item['reg_ip'],
                'reg_time'     => date('Y-m-d H:i:s', $item['reg_time']),
                'sex'          => $item['sex'],
                'is_block'     => $item['is_block'],
                'audit_status' => $item['audit_status'],
                'role_name'    => isset($roleMap[$item['user_id']]) ? $roleMap[$item['user_id']]['name'] : ''
            ];
        }
        $total = MemberModel::where($where)->count();
        $ret = [
            'items'       => $list,
            'page'        => intval($page),
            'limit'       => intval($limit),
            'total_items' => intval($total),
            'total_pages' => intval(ceil($total / $limit)),
        ];
        return json(self::createReturn(true, $ret));
    }

    /**
     * 拉黑、启用用户
     *
     * @return \think\response\Json
     */
    private function blockMember()
    {
        $user_id = $this->request->post('user_id', 0);
        $isBlock = $this->request->post('is_block', 0);
        if (empty($user_id)) {
            return self::makeJsonReturn(false, null, '参数异常');
        }
        return json(MemberService::blockMember($user_id, $isBlock));
    }

    /**
     * 批量 拉黑、启用用户
     *
     * @return \think\response\Json
     */
    private function batchBlockMember()
    {
        $user_ids = $this->request->post('user_ids', []);
        $isBlock = $this->request->post('is_block', 0);
        if (empty($user_ids)) {
            return self::makeJsonReturn(false, null, '参数异常');
        }
        $total = 0;
        foreach ($user_ids as $userId) {
            $res = MemberService::blockMember($userId, $isBlock);
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

    /**
     * 审核会员
     *
     * @return \think\response\Json|
     */
    private function auditMember()
    {
        $user_id = $this->request->post('user_id', 0);
        $audit_status = $this->request->post('audit_status', 0);
        if (empty($user_id)) {
            return self::makeJsonReturn(false, '', '请选择会员');
        }
        $res = MemberService::auditMember($user_id, $audit_status);
        if ($res) {
            return self::makeJsonReturn(true, null, '操作成功');
        }
        return self::makeJsonReturn(false, null, '操作失败');
    }

    // TODO 删除会员
    private function deleteMember()
    {
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
            $data = $this->request->post();
            if (!isset($data['username']) || empty($data['username'])) {
                return json(self::createReturn(false, null, '用户名不能为空'));
            }
            if (!isset($data['nickname']) || empty($data['nickname'])) {
                return json(self::createReturn(false, null, '呢称不能为空'));
            }
            if (!isset($data['role_id']) || empty($data['role_id'])) {
                return json(self::createReturn(false, null, '角色不能为空'));
            }
            if (empty($data['password']) || $data['password'] !== $data['password_confirm']) {
                return json(self::createReturn(false, null, '密码不一致'));
            }
            $res = MemberService::addOrEditMember($data);
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
            $data = $this->request->post();
            if (!isset($data['username']) || empty($data['username'])) {
                return json(self::createReturn(false, null, '用户名不能为空'));
            }
            if (!isset($data['nickname']) || empty($data['nickname'])) {
                return json(self::createReturn(false, null, '呢称不能为空'));
            }
            if (!isset($data['role_id']) || empty($data['role_id'])) {
                return json(self::createReturn(false, null, '角色不能为空'));
            }
            if (!empty($data['password'])) {
                if ($data['password'] !== $data['pwdconfirm']) {
                    return json(self::createReturn(false, null, '密码不一致'));
                }
            }
            $res = MemberService::addOrEditMember($data);
            return json($res);
        }
        // 详情
        if ($this->request->isGet() && $this->request->param('_action') === 'getDetail') {
            $user_id = $this->request->param('user_id');
            $memberModel = new MemberModel();
            $res = $memberModel->where('user_id', $user_id)->withoutField('reg_ip,reg_time,update_time,encrypt,password')->find();
            if (!$res) {
                return self::makeJsonReturn(false, null, '找不到用户');
            }
            return self::makeJsonReturn(true, $res->toArray());
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
        $list = $RoleModel->where('status', MemberRoleModel::STATUS_YES)->select()->toArray() ?: [];
        return json(self::createReturn(true, $list));
    }


}