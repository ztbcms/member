<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 9:00
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\service\MemberFieldService;
use think\facade\View;

/**
 * 字段管理
 * Class Field
 * @package app\member\controller
 */
class Field extends AdminController
{

    public function lists()
    {
        return View::fetch();
    }

    /**
     * 获取字段列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        $modelid = $this->request->get('modelid', 0);
        $MemberFieldService = new MemberFieldService();
        $data = $MemberFieldService->getModelFields($modelid);
        if (!$data) {
            return self::makeJsonReturn(false, [], $MemberFieldService->getError() ?: '获取失败');
        }
        return self::makeJsonReturn(true, $data);
    }

    /**
     * 删除字段 支持批量
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delFields()
    {
        //字段ID
        $fieldIds = $this->request->post('fieldid', [], 'intval');
        $MemberFieldService = new MemberFieldService();
        $res = $MemberFieldService->delFields($fieldIds);
        if (!$res) {
            return self::makeJsonReturn(false, '', $MemberFieldService->getError() ?: '删除失败');
        }
        return self::makeJsonReturn(true, '', '删除成功');
    }

    /**
     * 更新字段排序
     * @return \think\response\Json
     */
    public function listOrder()
    {
        if ($this->request->isPost()) {
            $postData = $this->request->post();
            if (empty($postData)) {
                return self::makeJsonReturn(false, [], '请选择!');
            }
            $MemberFieldService = new MemberFieldService();
            $res = $MemberFieldService->listOrder($postData);
            if ($res) {
                cache('Model', NULL);
                cache('ModelField', NULL);
                return self::makeJsonReturn(true, $res, '排序更新成功!');
            } else {
                return self::makeJsonReturn(false, $res, '排序失败!');
            }
        }
    }

    /**
     * 字段的启用与禁用【批量】
     * @return \think\response\Json
     */
    public function disabled()
    {
        $fieldIds = $this->request->post('fieldid', [], 'intval');
        $disabled = $this->request->post('disabled');
        $disabled = (int)$disabled ? 0 : 1;

        $MemberFieldService = new MemberFieldService();
        $res = $MemberFieldService->disabled($fieldIds, $disabled);

        if ($res) {
            cache('Model', NULL);
            cache('ModelField', NULL);
            return self::makeJsonReturn(true, $res, '操作成功!');
        } else {
            return self::makeJsonReturn(false, $res, '操作失败!');
        }
    }

}
