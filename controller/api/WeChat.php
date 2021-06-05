<?php
/**
 * User: cycle_3
 * Date: 2020/11/28
 * Time: 11:47
 */

namespace app\member\controller\api;

use app\BaseController;
use app\member\model\MemberUserModel;
use app\member\service\MemberUserService;
use app\wechat\service\MiniService;

/**
 * 微信登录模块 （依赖微信模块）
 * Class WeChat
 * @package app\member\controller\api
 */
class WeChat extends BaseController
{

    /**
     * 微信小程序获取手机号登录
     * @param $appid
     * @return \think\response\Json
     */
    public function getUserWeChatPhone()
    {
        $code = input('post.code', '', 'trim');
        $iv = input('post.iv', '', 'trim');
        $encryptedData = input('encryptedData', '', 'trim');
        $appid = input('appid', '', 'trim');
        $MiniService = new MiniService($appid);
        $res = $MiniService->getPhoneNumberByCode($code, $iv, $encryptedData);
        if ($res['status']) {
            //当获取手机号成功的情况，注册账号
            $phoneData = $res['data'];
            $MemberUserService = new MemberUserService();
            $res = $MemberUserService->getUserPhoneToken($phoneData['phoneNumber'], $phoneData['open_id'], 'open_id');
            return json($res);
        } else {
            return json($res);
        }
    }

    /**
     * 微信小程序获取授权登录 (更新用户数据)
     * @return \think\response\Json
     */
    public function getUserWeChat(){
        $code = input('post.code', '', 'trim');
        $userInfo = input('post.userInfo', '', 'trim');
        $appid = input('appid', '', 'trim');
        $MiniService = new MiniService($appid);
        $res = $MiniService->getOpenid($code);
        if($res['status']) {
            $MemberUserModel = new MemberUserModel();
            $memberDetails = $MemberUserModel
                ->where('source','=',$res['data']['openid'])
                ->where('source_type','=','open_id')
                ->findOrEmpty();
            if(!$memberDetails->isEmpty()) {
                $memberDetails->nickname = $userInfo['nickName'] ?? '';
                $memberDetails->sex = $userInfo['gender'] ?? '';
                $memberDetails->avatar = $userInfo['avatarUrl'] ?? '';
                $memberDetails->update_time = time();
                $memberDetails->save();
            }
        }
        return json($res);
    }

}