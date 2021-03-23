<?php
/**
 * Author: jayinton
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberModel;
use app\member\model\MemberTagBindModel;
use app\member\model\MemberTagModel;
use app\member\model\MemberUserModel;
use app\member\service\MemberService;
use app\member\service\MemberUserService;
use think\Request;

class Member extends AdminController
{
    /**
     * 会员列表
     */
    function index(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 15);
        $action = $request->get('_action');
        if ($request->isGet() && $action == 'getList') {
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

            $res = MemberUserService::getList($where, $page, $limit);
            return json($res);
        } elseif ($request->isPost() && $request->param('_action') == 'blockMember') {
            return $this->blockMember();
        } elseif ($request->isPost() && $request->param('_action') == 'batchBlockMember') {
            return $this->batchBlockMember();
        }elseif ($request->isPost() && $request->param('_action') == 'auditMember') {
            return $this->auditMember();
        }elseif ($request->isPost() && $request->param('_action') == 'batchAuditMember') {
            return $this->batchAuditMember();
        }
        return view();
    }

    /**
     * 获取用户列表
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * @deprecated
     */
    function getUserList()
    {
        $limit = $this->request->get('limit', 15);
        $param = $this->request->param();
        $where = [];
        if (!empty($param['datetime'])) {
            $where[] = ['reg_time', 'between', [$param['datetime'][0], $param['datetime'][1]]];
        }
        // 审核状态
        if (isset($param['checked']) && $param['checked'] != '') {
            $where[] = ['checked', '=', $param['checked']];
        }
        // 拉黑状态
        if (isset($param['is_block']) && $param['is_block'] != '') {
            $where[] = ['is_block', '=', $param['is_block']];
        }
        // 用户名、用户id
        if (!empty($param['search'])) {
            $where[] = ['username|user_id', 'like', $param['search']];
        }
        // 用户标签
        if (!empty($param['tag_name'])) {
            $tagIds = MemberTagModel::whereLike('tag_name', '%'.$param['tag_name'].'%')->column('tag_id');
            $userIds = MemberTagBindModel::whereIn('tag_id', $tagIds)->column('user_id');
            $where[] = ['user_id', 'in', $userIds];
        }
        $list = MemberUserService::getList($where, $limit);
        return self::makeJsonReturn(true, $list);
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
     * 删除用户
     *
     * @return \think\response\Json
     */
    public function delUser()
    {
        $userIds = $this->request->post('user_id', 0);
        if (empty($userIds)) {
            return self::makeJsonReturn(false, '', '请选择');
        }
        $res = MemberUserService::batchDelUser($userIds);
        if ($res) {
            return self::makeJsonReturn(true, '', '删除成功');
        }
        return self::makeJsonReturn(false, '', '删除失败');
    }

    /**
     * 审核会员
     *
     * @return \think\response\Json|
     * @deprecated
     */
    public function auditUser()
    {
        $userIds = $this->request->post('user_id', 0);
        if (empty($userIds)) {
            return self::makeJsonReturn(false, '', '请选择');
        }
        $res = MemberUserService::auditUser($userIds, MemberUserModel::IS_CHECKED);
        if ($res) {
            //更新成功触发，审核通过行为 TODO
//            Hook::listen('member_verify', MemberBehaviorParam::create(['userid' => $val['userid']]));
            return self::makeJsonReturn(true, '', '审核成功');
        }
        return self::makeJsonReturn(false, '', '审核失败');
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


}