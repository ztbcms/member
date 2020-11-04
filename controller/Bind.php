<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 13:55
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\service\MemberConnectService;
use think\facade\View;

/**
 * 用户绑定关联管理
 * Class Bind
 * @package app\member\controller
 */
class Bind extends AdminController
{
    protected $noNeedPermission = ['getBindDetail'];

    /**
     * 用户绑定
     * @return string
     */
    public function bindDetail()
    {
        return View::fetch();
    }

    /**
     * 获取绑定信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBindDetail()
    {
        $userId = $this->request->get('user_id', 0);
        $list = MemberConnectService::getBindDetail($userId);
        if ($list->isEmpty()) {
            return self::makeJsonReturn(false, '', '');
        }
        return self::makeJsonReturn(true, $list, '');
    }

    /**
     * 与第三方平台解绑
     * @return \think\response\Json
     */
    public function unBindUser()
    {
        $userId = $this->request->post('user_id', 0);
        $bindType = $this->request->post('bind_type', 0);
        $res = MemberConnectService::unBindUser($userId, $bindType);
        return json($res);
    }

}
