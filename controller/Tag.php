<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 8:56
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\service\MemberTagService;
use app\member\validate\MemberTagValidate;
use think\facade\View;

/**
 * 标签管理
 * Class Tag
 * @package app\member\controller
 */
class Tag extends AdminController
{
    /**
     * 标签列表页
     * @return string
     */
    public function lists()
    {
        return View::fetch();
    }

    /**
     * 获取标签列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        $limit = $this->request->get('limit', 15);
        $list = MemberTagService::getTagsList(true, $limit);
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
        $tagName = $this->request->post('tag_name', '');
        $tagId = $this->request->post('tag_id', 0);
        $sort = $this->request->post('sort', 0);
        $isShow = $this->request->post('is_show', 0);

        //进行数据验证
        $validate = new MemberTagValidate();
        if (!$validate->check($this->request->param())) {
            return self::makeJsonReturn(false, [], $validate->getError());
        }
        $res = MemberTagService::addEditTag($tagName, $tagId, $sort, $isShow);
        if($res){
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
        $tagId = $this->request->get('tag_id', 0);
        $info = MemberTagService::getTagInfo($tagId);
        return self::makeJsonReturn(true, $info);
    }

    /**
     * 删除标签
     * @return array
     */
    public function delTag()
    {
        $tagId = $this->request->post('tag_id', 0);
        return MemberTagService::deleteItem($tagId);
    }

    /**
     * 更新标签
     * @return \think\response\Json
     */
    public function updateField()
    {
        $tagId = $this->request->post('tag_id', 0);
        $field = $this->request->post('field', 0);
        $value = $this->request->post('value', 0);
        $res = MemberTagService::updateField($tagId,$field,$value);
        if($res){
            return self::makeJsonReturn(true, [], '更新成功');
        }
        return self::makeJsonReturn(false, [], '更新失败');
    }

}
