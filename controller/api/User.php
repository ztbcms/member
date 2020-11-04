<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 11:19
 */

namespace app\member\controller\api;

use app\BaseController;
use app\member\service\MemberUserService;

/**
 * 用户接口
 * Class User
 * @package app\member\controller\api
 */
class User extends BaseController
{
    // 创建一个普通用户
    // home/member/api.user/register
    public function register()
    {
        $username = $this->request->post('username', null); // 用户名
        $password = $this->request->post('password', null);

        $MemberUserService = new MemberUserService();
        $res = $MemberUserService->userRegister($username, $password);
        if ($res) {
            return self::makeJsonReturn(true, [], '创建成功');
        }
        return self::makeJsonReturn(false, [], '创建失败');
    }

    // 第三方绑定
    // home/member/api.user/bindApp
    public function bindApp()
    {
        $userId = $this->request->post('user_id', null);
        $appType = $this->request->post('app_type', null);
        $openId = $this->request->post('open_id', null);
        $MemberUserService = new MemberUserService();
        $res = $MemberUserService->bindApp($userId, $appType, $openId);
        if ($res) {
            return self::makeJsonReturn(true, [], '绑定成功');
        }
        return self::makeJsonReturn(false, [], '绑定失败');
    }

    // 第三方登录授权token

}
