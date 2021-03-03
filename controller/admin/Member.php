<?php
/**
 * Author: jayinton
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberTagBindModel;
use app\member\model\MemberTagModel;
use app\member\model\MemberUserModel;
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
        if($request->isGet() && $action == 'getList'){
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
                switch ($param['tab']){
                    case 1:
                        $where[] = ['check_status', '=', 0];
                        break;
                    case 2:
                        $where[] = ['check_status', '=', 2];
                        break;
                    case 3:
                        $where[] = ['is_block', '=', 1];
                        break;
                }
            }

            $res = MemberUserService::getList($where, $page, $limit);
            return json($res);
        }
        return view();
    }

    /**
     * 获取用户列表
     * @deprecated
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
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
            $tagIds = MemberTagModel::whereLike('tag_name', '%' . $param['tag_name'] . '%')->column('tag_id');
            $userIds = MemberTagBindModel::whereIn('tag_id', $tagIds)->column('user_id');
            $where[] = ['user_id', 'in', $userIds];
        }
        $list = MemberUserService::getList($where, $limit);
        return self::makeJsonReturn(true, $list);
    }

    /**
     * 拉黑、启用用户
     * @return \think\response\Json
     */
    public function blockUser()
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

    /**
     * 删除用户
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
     * @return \think\response\Json|
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
     * 取消审核会员
     * @return \think\response\Json|
     */
    public function cancelAuditUser()
    {
        $userIds = $this->request->post('user_id', 0);
        if (empty($userIds)) {
            return self::makeJsonReturn(false, '', '请选择');
        }
        $res = MemberUserService::auditUser($userIds, MemberUserModel::NO_CHECKED);
        if ($res) {
            //更新成功触发，审核是取消行为 TODO
//            Hook::listen('member_unverify', MemberBehaviorParam::create(['userid' => $val['userid']]));
            return self::makeJsonReturn(true, '', '取消审核成功');
        }
        return self::makeJsonReturn(false, '', '取消审核失败');
    }
}