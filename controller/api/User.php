<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 11:19
 */

namespace app\member\controller\api;

use app\BaseController;
use app\member\service\MemberConnectService;
use app\member\service\MemberUserService;
use app\common\util\Encrypt;

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
        $username = input('username', ''); // 用户名
        $password = input('password', '');

        $MemberUserService = new MemberUserService();
        $userId = $MemberUserService->userRegister($username, $password);
        if ($userId) {
            return self::makeJsonReturn(true, [
                'user_id' => $userId,
                'token' => Encrypt::authcode((int) $userId, Encrypt::OPERATION_ENCODE,'ZTBCMS',86400)
            ], '创建成功');
        }
        return self::makeJsonReturn(false, [], '创建失败');
    }

    // 第三方绑定
    // home/member/api.user/bindApp
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
    // home/member/api.user/loginByBindOpenId
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

    /**
     * 获取用户token
     * @return \think\response\Json
     */
    public function createSimulationToken(){
        //用户id
        $user_id = input('user_id','0','trim');

        //token有效期
        $expiry = 86400;

        $token = Encrypt::authcode((int) $user_id, Encrypt::OPERATION_ENCODE,'ZTBCMS',$expiry);
        return json(self::createReturn(true,[
            'token' => $token,
            'expiry' => $expiry
        ]));
    }
}
