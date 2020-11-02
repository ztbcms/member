<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:22
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberTagBindModel;
use app\member\model\MemberTagModel;
use app\member\model\MemberUserModel;
use app\member\service\MemberTagBindService;
use app\member\service\MemberUserService;
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

    /**
     * 添加用户页面
     * @return string
     */
    public function add()
    {
        return View::fetch();
    }

    /**
     * 编辑用户页面
     * @return string
     */
    public function edit()
    {
        return View::fetch();
    }

    /**
     * 获取用户信息
     * @return \think\response\Json
     */
    public function getDetail()
    {
        $userId = $this->request->get('user_id', 0);
        $info = MemberUserService::getDetail($userId);
        if ($info->isEmpty()) {
            return self::makeJsonReturn(false, '', '用户不存在');
        }
        return self::makeJsonReturn(true, $info, '');
    }

    /**
     * 添加用户
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addUser()
    {
        $post = $this->request->post();
        // 创建主信息
        $MemberUserService = new MemberUserService();
        Db::startTrans();
        if ($post['password_confirm'] != $post['password']) return self::makeJsonReturn(false, '', '两次密码不一致');
        $userInfo = $MemberUserService->userRegister($post['username'], $post['password'], $post['email']);
        if (!$userInfo) {
            return self::makeJsonReturn(false, '', $MemberUserService->getError() ?: '创建失败');
        }
        // 创建附加资料
        if (!empty($userInfo['user_id'])) {
            $userId = $userInfo['user_id'];
            // 添加附表信息
            $this->addMemberData($userId, $post['modelid'], $post['info']);
            // 添加标签
            MemberTagBindService::bindUserTag($userId, $post['tag_ids']);
            Db::commit();
            return self::makeJsonReturn(true, '', '添加会员成功');
        } else {
            Db::rollback();
            return self::makeJsonReturn(false, '', $MemberUserService->getError() ?: '创建失败');
        }
    }

    // 编辑用户 TODO
    public function editUser()
    {
        if ($this->request->isPost()) {
            $userId = $this->request->post('user_id', 0, 'intval');
            $postData = $this->request->post();

            // 模型信息相关更新 TODO
            $info = $postData['info'];
            $modelId = $this->request->post('modelid', 0, 'intval');

            Db::startTrans();
//            $userInfo = MemberUserModel::where('user_id', $userId)->findOrEmpty();

            // 获取用户信息
            $MemberUserService = new MemberUserService();
            $userInfo = $MemberUserService->getLocalUser($userId);
            if (empty($userInfo)) {
                return self::makeJsonReturn(false, '', '该会员不存在');
            }
            // VIP过期时间
            $data['overduedate'] = strtotime($postData['overduedate']);

            // 模型信息 TODO
//                $ContentModel = Db::name('model');
//                if ($userInfo['modelid'] == $modelId && $info) {
//                    //详细信息验证
//                    $content_input = new \content_input($modelId);
//                    $inputinfo = $content_input->get($info, 2);
//                    if ($inputinfo) {
//                        //数据验证
//                        $inputinfo = $ContentModel->token(false)->create($inputinfo, 2);
//                        if (false == $inputinfo) {
//                            $ContentModel->tokenRecovery($post);
//                            $this->error($ContentModel->getError());
//                        }
//                    } else {
//                        $ContentModel->tokenRecovery($post);
//                        $this->error($content_input->getError());
//                    }
//                    //检查详细信息是否已经添加过
//                    if ($ContentModel->where(array("userid" => $userid))->find()) {
//                        $ContentModel->where(array("userid" => $userid))->save($inputinfo);
//                    } else {
//                        $inputinfo['userid'] = $userid;
//                        $ContentModel->add($inputinfo);
//                    }
//                }

            //判断是否需要删除头像
            $isDelAvatar = $this->request->post('delavatar', 0);
            if ($isDelAvatar) {
                MemberUserService::delAvatar($userInfo['user_id']);
            }
            //修改基本资料
            if ($userInfo['username'] != $postData['username'] || !empty($postData['password']) || $userInfo['email'] != $postData['email']) {
                $MemberUserService = new MemberUserService();
                $editRes = $MemberUserService->userEdit($postData['username'], '', $postData['password'], $postData['email'], 1);
                if (!$editRes) {
                    return self::makeJsonReturn(false, '', $MemberUserService->getError());
                }
            }
            unset($postData['username'], $postData['password'], $postData['email']);
            // 更新标签
            MemberTagBindService::bindUserTag($userId, $postData['tag_ids']);
            unset($postData['tag_ids']);
            unset($postData['tags_name']);
            unset($postData['password_confirm']);
            unset($postData['info']);
            //更新除基本资料外的其他信息
            if (false === MemberUserModel::where('user_id', $userId)->save($postData)) {
                return self::makeJsonReturn(false, '', '更新失败');
            }
            DB::commit();
            return self::makeJsonReturn(true, '', '更新成功');
        }
    }

    /**
     * 添加附表信息
     * @param $userId
     * @param $modelId
     * @param $info
     */
    protected function addMemberData($userId, $modelId, $info)
    {
        if ($modelId) {
            $tablename = getModel($modelId, 'tablename');
            // $info 附表模型字段 TODO
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
        $res = MemberUserService::blockUser($userIds,$isBlock);
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
        $res = MemberUserService::auditUser($userIds,MemberUserModel::IS_CHECKED);
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
        $res = MemberUserService::auditUser($userIds,MemberUserModel::NO_CHECKED);
        if ($res) {
            //更新成功触发，审核是取消行为 TODO
//            Hook::listen('member_unverify', MemberBehaviorParam::create(['userid' => $val['userid']]));
            return self::makeJsonReturn(true, '', '取消审核成功');
        }
        return self::makeJsonReturn(false, '', '取消审核失败');
    }

}
