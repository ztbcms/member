<?php

namespace app\member\controller\api;

use app\BaseController;
use app\member\service\MemberConnectService;
use app\member\service\MemberUserService;
use app\common\util\Encrypt;
use app\member\service\TokenService;

/**
 * 用户接口
 * Class User
 * @package app\member\controller\api
 */
class User extends BaseController
{
    // 登录
    // /member/api.login/register
    function login()
    {
        $username = input('username', ''); // 用户名
        $password = input('password', '');
        $MemberUserService = new MemberUserService();
        $res =  $MemberUserService->userLogin($username, $password);
        if($res['status']){
            return self::makeJsonReturn(true, [
                'user_info' => $res['data'],
                'token'   => TokenService::encode($res['data']['user_id'])
            ], '登录成功');
        }

        return json($res);
    }
    // 创建一个普通用户
    // /member/api.user/register
    public function register()
    {
        $username = input('username', ''); // 用户名
        $password = input('password', '');

        $MemberUserService = new MemberUserService();
        $res = $MemberUserService->userRegister($username, $password);
        if ($res['status']) {
            $userId = $res['data']['user_id'];
            return self::makeJsonReturn(true, [
                'user_id' => $userId,
                'token'   => TokenService::encode($userId)
            ], '注册成功');
        }
        return self::makeJsonReturn(false, [], $res['msg']);
    }

    // 第三方绑定
    // /member/api.user/bindApp
    public function bindApp()
    {
        $userId = $this->request->post('user_id', '');
        $appType = $this->request->post('app_type', '');
        $openId = $this->request->post('open_id', '');
        $MemberConnectService = new MemberConnectService();
        $res = $MemberConnectService->bindApp($userId, $appType, $openId);
        if ($res) {
            return self::makeJsonReturn(true, [], '绑定成功');
        }
        return self::makeJsonReturn(false, [], '绑定失败');
    }

    // 第三方登录授权 获取用户凭证
    // /member/api.user/loginByBindOpenId
    public function loginByBindOpenId()
    {
        $openId = $this->request->post('open_id', null);
        // 查询绑定的用户
        $userBindInfo = MemberConnectService::getUid($openId);
        if ($userBindInfo->isEmpty()) {
            return self::makeJsonReturn(false, [], '查询不到绑定信息');
        }
        // 获取token
        $token = MemberUserService::loginToken((int)$userBindInfo['user_id'], $openId, $userBindInfo['app_type'], $userBindInfo['app_name']);
        if ($token) {
            return self::makeJsonReturn(true, ['token' => $token]);
        }
        return self::makeJsonReturn(false, [], '登录失败');
    }


}
