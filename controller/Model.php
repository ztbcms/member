<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 18:22
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberModelModel;
use app\member\service\MemberModelService;
use think\facade\Config;
use think\facade\Db;
use think\facade\View;

/**
 * 会员模型
 * Class Model
 * @package app\member\controller
 */
class Model extends AdminController
{
    /**
     * 模型列表页
     * @return string
     */
    public function lists()
    {
        return View::fetch();
    }

    /**
     * 添加编辑页面
     * @return string
     */
    public function detail()
    {
        // 获取数据库配置
        $setting = Config::get('database');
        $prefix = $setting['connections'][$setting['default']]['prefix'];
        return View::fetch('', ['prefix' => $prefix]);
    }

    /**
     * 添加编辑操作
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function addEditModel()
    {
        $param = $this->request->param();
        $modelId = $this->request->param('modelid', 0);
        // 模型名称不能为空
        if (empty($param['name'])) {
            return self::makeJsonReturn(false, [], '模型名称不能为空');
        }
        // 模型名存在
        $checkName = Db::name('model')
            ->where('modelid', '<>', $modelId)
            ->where('name', $param['name'])
            ->findOrEmpty();
        if ($checkName) {
            return self::makeJsonReturn(false, [], '模型名已存在');
        }
        // 表名不能为空
        if (empty($param['tablename'])) {
            return self::makeJsonReturn(false, [], '表名不能为空');
        }
        // 表名存在
        $checkTableName = Db::name('model')->where('tablename', $param['tablename'])
            ->where('modelid', '<>', $modelId)
            ->findOrEmpty();
        if ($checkTableName) {
            return self::makeJsonReturn(false, [], '表名存在');
        }
        // 表名只能是英文名
        if (!preg_match('/^[a-zwd_]+$/i', $param['tablename'])) {
            return self::makeJsonReturn(false, [], '模型表键名只支持英文');
        }
        $MemberModelService = new MemberModelService();
        $res = $MemberModelService->addEditModel($param['name'], $param['description'], $param['tablename'], $param['disabled'], $modelId);
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
        $modelId = $this->request->get('model_id', 0);
        $MemberModelService = new MemberModelService();
        $detail = $MemberModelService->getDetail($modelId);
        if (!$detail) {
            return self::makeJsonReturn(false, [], $MemberModelService->getError());
        }
        return self::makeJsonReturn(true, $detail, '');
    }

    /**
     * 获取model 列表
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getList()
    {
        $MemberModelService = new MemberModelService();
        $list = $MemberModelService->getList();
        return self::makeJsonReturn(true, $list, '');
    }

    /**
     * 删除模型
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delModel()
    {
        $modelId = $this->request->post('model_id', 0);
        $MemberModelService = new MemberModelService();
        $res = $MemberModelService->deleteModel($modelId);
        if (!$res) {
            return self::makeJsonReturn(false, [], $MemberModelService->getError() ?: '删除失败');
        }
        return self::makeJsonReturn(true, [], '删除成功');
    }
}
