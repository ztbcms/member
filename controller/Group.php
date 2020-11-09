<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 17:58
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberGroupModel;
use app\member\service\MemberGroupService;
use app\member\service\MemberTagService;
use app\member\validate\MemberGroupValidate;
use app\member\validate\MemberTagValidate;
use think\facade\View;

/**
 * 会员组
 * Class Group
 * @package app\member\controller
 */
class Group extends AdminController
{
    /**
     * 获取全部会员组 【添加会员使用】
     * @return \think\response\Json
     */
    public function getGroupList()
    {
        $list = MemberGroupModel::column('group_id,group_name');
        return self::makeJsonReturn(true, $list);
    }

    /**
     * 会员组列表页
     * @return string
     */
    public function lists()
    {
        return View::fetch();
    }

    /**
     * 获取会员组列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        $limit = $this->request->get('limit', 15);
        $where = [];
        $search = $this->request->get('search', '');
        if (!empty($search)) {
            $where[] = ['group_name', 'like', '%' . $search . '%'];
        }
        // 查询会员组有多少用户
        $list = MemberGroupService::getGroupList(true, $limit, $where);
        foreach ($list as &$item) {
            $item['user_count'] = MemberGroupService::getUserCountByGroupId($item['group_id']);
        }
        return self::makeJsonReturn(true, $list);
    }

    /**
     * 详情页
     * @return string
     */
    public function detail()
    {
        return View::fetch();
    }

    /**
     * 添加 编辑
     * @return \think\response\Json
     */
    public function addEdit()
    {
        $param = $this->request->post();
        //进行数据验证
        $validate = new MemberGroupValidate();
        if (!$validate->check($param)) {
            return self::makeJsonReturn(false, [], $validate->getError());
        }
        $res = MemberGroupService::addEditGroup($param);
        if ($res) {
            return self::makeJsonReturn(true, [], '操作成功');
        }
        return self::makeJsonReturn(false, [], '操作失败');
    }

    /**
     * 获取详情
     * @return \think\response\Json
     */
    public function getDetail()
    {
        $groupId = $this->request->get('group_id', 0);
        $info = MemberGroupService::getGroupInfo($groupId);
        return self::makeJsonReturn(true, $info);
    }

    /**
     * 删除会员组
     * @return array
     */
    public function delGroup()
    {
        $groupIds = $this->request->post('group_id', 0);
        return MemberGroupService::deleteItem($groupIds);
    }

    /**
     * 更新会员组
     * @return \think\response\Json
     */
    public function updateField()
    {
        $tagId = $this->request->post('tag_id', 0);
        $field = $this->request->post('field', 0);
        $value = $this->request->post('value', 0);
        $res = MemberGroupService::updateField($tagId, $field, $value);
        if ($res) {
            return self::makeJsonReturn(true, [], '更新成功');
        }
        return self::makeJsonReturn(false, [], '更新失败');
    }

    /**
     * 更新字段排序
     * @return \think\response\Json
     */
    public function listOrder()
    {
        if ($this->request->isPost()) {
            $postData = $this->request->post('data');
            $res = MemberGroupService::listOrder($postData);
            if ($res) {
                return self::makeJsonReturn(true, $res, '排序更新成功!');
            } else {
                return self::makeJsonReturn(false, $res, '排序失败!');
            }
        }
    }
}
