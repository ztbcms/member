<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\api;

use app\BaseController;
use app\member\service\MemberService;

/**
 * 网站登录模块
 * Class WeChat
 * @package app\member\controller\api
 */
class Web extends BaseController
{

    /**
     * 会员注册
     * @return array
     */
    public function register()
    {
        $username = input('username','','trim');
        $password = input('password','','trim');
        $res = MemberService::membeRegister($username,$password,$username,'web');
        return json($res);
    }

    /**
     * 会员登录
     * @return \think\response\Json
     */
    public function login()
    {
        $username = input('username','','trim');
        $password = input('password','','trim');
        $res = MemberService::memberLogin($username,$password);
        return json($res);
    }



}