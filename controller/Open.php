<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 9:47
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\service\MemberOpenService;
use think\facade\View;

/**
 * 第三方平台管理
 * Class Open
 * @package app\member\controller
 */
class Open extends AdminController
{
    /**
     * 第三方平台
     * @return string
     */
    public function index()
    {
        return View::fetch();
    }

    /**
     * @return string
     */
    public function detail()
    {
        return View::fetch();
    }

    /**
     * 获取第三方平台列表(暂定)
     * @return \think\response\Json
     */
    public function getTypeList()
    {
        $data = [
            [
                'label' => '新浪微博',
                'value' => 'weibo',
            ],
            [
                'label' => 'qq',
                'value' => 'qq',
            ],
        ];
        return self::makeJsonReturn(true, $data);
    }

    /**
     * 获取详情
     * @return \think\response\Json
     */
    public function getDetail()
    {
        $id = $this->request->get('id', 0);
        $MemberOpenService = new MemberOpenService();
        $info = $MemberOpenService->getDetail($id);
        if (!$info) {
            return self::makeJsonReturn(false, [], $info->getError() ?: '获取失败');
        }
        return self::makeJsonReturn(true, $info);
    }


    /**
     * 获取列表
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getList()
    {
        $limit = $this->request->get('limit', 15);
        $MemberOpenService = new MemberOpenService();
        $list = $MemberOpenService->getList([], $limit);
        return self::makeJsonReturn(true, $list);
    }

    /**
     * 添加编辑应用
     * @return \think\response\Json
     */
    public function addEditApp()
    {
        $param = $this->request->param();
        $appId = $this->request->param('id', 0);
        $MemberOpenService = new MemberOpenService();
        $res = $MemberOpenService->addEditApp($param['app_type'], $param['app_key'], $param['app_secret'], $appId);
        if ($res) {
            return self::makeJsonReturn(true, [], '操作成功');
        }
        return self::makeJsonReturn(false, [], '操作失败');
    }

    /**
     * 删除 应用
     * @return \think\response\Json
     */
    public function delApp()
    {
        $id = $this->request->post('id', 0);
        $MemberOpenService = new MemberOpenService();
        $res = $MemberOpenService->deleteApp($id);
        if ($res) {
            return self::makeJsonReturn(true, [], '操作成功');
        }
        return self::makeJsonReturn(false, [], $MemberOpenService->getError() ?: '操作失败');
    }
}
