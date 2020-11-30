<?php
/**
 * User: cycle_3
 * Date: 2020/11/28
 * Time: 11:47
 */

namespace app\member\controller\api;

use app\BaseController;
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
    public function getUserWeChatPhone(){
        $code = input('post.code', '', 'trim');
        $iv = input('post.iv', '', 'trim');
        $encryptedData = input('encryptedData', '', 'trim');
        $appid = input('appid','','trim');
        $MiniService = new MiniService($appid);
        $res = $MiniService->getPhoneNumberByCode($code, $iv, $encryptedData);
        if($res['status']) {
            //当获取手机号成功的情况，注册账号
            $phoneData = $res['data'];
            $MemberUserService = new MemberUserService();
            $res = $MemberUserService->getLoginRegisterUser($phoneData['phoneNumber'], $phoneData['open_id']);
            if($res['data']['user_id']) {
                //更新用户数据
                $phoneData['phone'] = $phoneData['phoneNumber'];
                $MemberUserService->sysUserInfo($res['data']['user_id'],$phoneData);
            }
            return json($res);
        } else {
            return json($res);
        }
    }

    /**
     * 微信小程序获取授权登录
     * @return \think\response\Json
     */
    public function getUserWeChat(){
        $code = input('post.code', '', 'trim');
        $iv = input('post.iv','','trim');
        $encryptedData = input('post.encrypted_data','','trim');
        $appid = input('appid','','trim');
        $MiniService = new MiniService($appid);
        $res = $MiniService->getUserInfoByCode($code, $iv, $encryptedData);
        if($res['status']) {
            //获取微信授权登录
            $weChatData = $res['data'];
            $MemberUserService = new MemberUserService();
            $res = $MemberUserService->getLoginRegisterUser($weChatData['open_id'], $weChatData['open_id']);
            if($res['data']['user_id']) {
                //更新用户数据
                $weChatData['sex'] = $weChatData['gender'];
                $weChatData['userpic'] = $weChatData['avatar_url'];
                $weChatData['nickname'] = $weChatData['nick_name'];
                $MemberUserService->sysUserInfo($res['data']['user_id'],$weChatData);
            }
            return json($res);
        }
        return json($res);
    }

}