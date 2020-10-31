<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:22
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberModel;
use app\member\service\MemberService;
use think\facade\Db;
use think\facade\View;

/**
 * 会员管理
 * Class User
 * @package app\member\controller
 */
class User extends AdminController
{
    /**
     * 用户列表
     * @return string
     */
    public function lists()
    {
        return View::fetch();
    }

    /**
     * 未审核用户列表
     * @return string
     */
    public function unAuditLists()
    {
        return View::fetch();
    }

    public function add()
    {
        return View::fetch();
    }

    /**
     * 添加用户
     * @return \think\response\Json
     */
    public function addUser()
    {
        $post = $this->request->post();
        // 创建主信息
        $MemberService = new MemberService();
        Db::startTrans();
        $userInfo = $MemberService->userRegister($post['username'], $post['password'], $post['email']);
        if(!$userInfo){
            return self::makeJsonReturn(false,'',$MemberService->getError() ?: '创建失败');
        }
        if (!empty($userInfo)) {
            $userId = $userInfo['user_id'];
            if ($userId) {
                //添加附表信息
                $this->addMemberData($userId, $post['modelid'], $post['info']);
                Db::commit();
                return self::makeJsonReturn(true,'','添加会员成功');
            } else {
                Db::rollback();
                return self::makeJsonReturn(false,'',$MemberService->getError() ?: '创建失败-2');
            }
        } else {
            Db::rollback();
            return self::makeJsonReturn(false,'',$MemberService->getError() ?: '创建失败-1');
        }
    }

    /**
     * 添加附表信息
     * @param $userId
     * @param $modelId
     * @param $info
     */
    protected function addMemberData($userId, $modelId, $info) {
        if ($modelId) {
            $tablename = getModel($modelId, 'tablename');
            // $info 附表的字段 TODO
            $info['userid'] = $userId;
            Db::table($tablename)->save($info);
        }
    }

    /**
     * 获取用户列表
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getUserList()
    {
        $limit = $this->request->get('limit', 15);
        $param = $this->request->param();
        $model = new MemberModel();
        $list = $model->order('create_time', 'desc');
        if (!empty($param['datetime'])) {
            $list->whereBetweenTime('reg_time', $param['datetime'][0], $param['datetime'][1]);
        }
        // 审核状态
        if (isset($param['checked']) && $param['checked'] != '') {
            $list->where('checked', '=', $param['checked']);
        }
        // 拉黑状态
        if (isset($param['is_block']) && $param['is_block'] != '') {
            $list->where('is_block', $param['is_block']);
        }
        // 用户名、用户id
        if (!empty($param['search'])) {
            $list->whereLike('username|user_id', $param['search']);
        }
        $list = $list->paginate($limit);
        return self::makeJsonReturn(true, $list);
    }

    /**
     * 拉黑、启用用户
     * @return \think\response\Json
     */
    public function blockUser()
    {
        $userIds = $this->request->post('user_id', 0);
        if (empty($userIds)) {
            return self::makeJsonReturn(false, '', '请选择');
        }
        $isBlock = $this->request->post('is_block', 0);

        $count = 0;
        foreach ($userIds as $userId) {
            $count += MemberModel::where('user_id', $userId)->save(['is_block' => $isBlock]);
        }
        if ($count > 0) {
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
        $res = MemberModel::whereIn('user_id', $userIds)->useSoftDelete('delete_time', time())->delete();
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
        $count = 0;
        foreach ($userIds as $userId) {
            $count += MemberModel::where('user_id', $userId)->save(['checked' => MemberModel::IS_CHECKED]);
        }
        if ($count > 0) {
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
        $count = 0;
        foreach ($userIds as $userId) {
            $count += MemberModel::where('user_id', $userId)->save(['checked' => MemberModel::NO_CHECKED]);
        }
        if ($count > 0) {
            return self::makeJsonReturn(true, '', '取消审核成功');
        }
        return self::makeJsonReturn(false, '', '取消审核失败');
    }

}
