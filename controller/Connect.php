<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 18:17
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\service\MemberConnectService;
use think\facade\View;

/**
 * 第三方平台接入管理
 * Class Connect
 * @package app\member\controller
 */
class Connect extends AdminController
{
    public function lists()
    {
        return View::fetch();
    }

    /**
     * 获取授权过的token列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTokenList()
    {
        $limit = $this->request->get('limit', 15);
        $MemberConnectService = new MemberConnectService();
        $tokenList = $MemberConnectService->getTokenList(true, $limit);
        return self::makeJsonReturn(true, $tokenList);
    }

    /**
     * 删除token
     * @return \think\response\Json
     */
    public function delToken()
    {
        $tokenId = $this->request->post('token_id', 0);
        $MemberConnectService = new MemberConnectService();
        $res = $MemberConnectService->deleteToken($tokenId);
        if ($res) {
            return self::makeJsonReturn(true, '', '删除成功');
        }
        return self::makeJsonReturn(false, '', '删除失败');
    }

}
