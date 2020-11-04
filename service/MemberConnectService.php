<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 8:58
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberBindModel;
use app\member\model\MemberConnectTokenModel;
use app\member\model\MemberOpenModel;
use app\member\model\MemberTagModel;
use app\member\validate\MemberTagValidate;

/**
 * 第三方授权管理
 * Class MemberConnectService
 * @package app\member\service
 */
class MemberConnectService extends BaseService
{
    /**
     * 获取授权记录token
     * @param bool $isPage 是否需要分页
     * @param int $limit
     * @param array $where
     * @return \think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getTokenList($isPage = false, $limit = 15, $where = [])
    {
        if ($isPage) {
            return MemberConnectTokenModel::with('userName')->where($where)->paginate($limit);
        }
        return MemberConnectTokenModel::with('userName')->where($where)->select();
    }

    /**
     * 通过第三方凭证 获取系统用户id
     * @param $openId
     * @return array|\think\Model
     */
    static function getUid($openId)
    {
        return MemberBindModel::where('bind_open_id', $openId)->value('user_id');
    }

    /**
     * 删除token 记录
     * @param $userId
     * @param $bindType
     * @return array
     */
    static function unBindUser($userId, $bindType)
    {
        $bind = MemberBindModel::where('user_id', $userId)
            ->where('bind_type', $bindType)
            ->findOrEmpty();
        if ($bind->isEmpty()) {
            return self::createReturn(false, [], '绑定关系不存在');
        }
        $res = $bind->delete();
        if ($res) {
            return self::createReturn(true, [], '解绑成功');
        }
        return self::createReturn(false, [], '解绑失败');
    }

    /**
     * 删除token 记录
     * @param $tokenId
     * @return array
     */
    public function deleteToken($tokenId)
    {
        $token = MemberConnectTokenModel::where('token_id', $tokenId)->findOrEmpty();
        if ($token->isEmpty()) {
            return self::createReturn(false, [], 'token不存在');
        }
        $res = $token->delete();
        if ($res) {
            return self::createReturn(true, [], '删除成功');
        }
        return self::createReturn(false, [], '删除失败');
    }


    /**
     * 获取QQ授权地址
     * @param string $redirect_uri
     * @return string
     */
    public function getUrlConnectQQ($redirect_uri = '')
    {
        // qq配置
        $setting = MemberOpenModel::where('app_type', MemberOpenModel::TYPE_QQ)->findOrEmpty();
        $appKey = $setting['app_key'];
        $appSecret = $setting['aqq_secret'];
        if (empty($appKey) || empty($appSecret)) {
            $this->error = '没有进行QQ互联的相关配置，请配置后在继续使用！';
            return false;
        }

        $sState = md5('ztb' . request()->ip());
        session("state", $sState);

        //回调地址
        if (empty($redirect_uri)) {
            $redirect_uri = api_url('/member/index.bindqq/callback');
        }
        session("redirect_uri", $redirect_uri);

        //请求用户授权时向用户显示的可进行授权的列表
        $scope = "get_user_info,add_share,check_page_fans";
        //请求参数
        $aParam = array(
            "response_type" => "code",
            "client_id"     => $appKey,
            "redirect_uri"  => $redirect_uri,
            "scope"         => $scope,
            "state"         => $sState,
        );

        //对参数进行URL编码
        $aGet = array();
        foreach ($aParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        //请求地址
        $sUrl = "https://graph.qq.com/oauth2.0/authorize?";
        $sUrl .= join("&", $aGet);
        return $sUrl;
    }

    /**
     * 获取新浪微博授权地址
     * @param string $redirect_uri
     * @return string
     */
    public function getUrlConnectSinaWeibo($redirect_uri = '')
    {
        // 微博配置
        $setting = MemberOpenModel::where('app_type', MemberOpenModel::TYPE_WEIBO)->findOrEmpty();
        $appKey = $setting['app_key'];
        $appSecret = $setting['aqq_secret'];
        if (empty($appKey) || empty($appSecret)) {
            $this->error = '获取不到相关配置，新浪互联无法进行！';
            return false;
        }
        $sState = md5('ztb' . request()->ip());
        session("state", $sState);

        //回调地址
        if (empty($redirect_uri)) {
            $redirect_uri = api_url('/member/index.bindSina/callback');
        }
        session("redirect_uri", $redirect_uri);

        //请求参数
        $aParam = array(
            "client_id"    => $appKey, //申请应用时分配的AppKey。
            "redirect_uri" => $redirect_uri, //授权回调地址
            "state"        => $sState,
        );

        //对参数进行URL编码
        $aGet = array();
        foreach ($aParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        //请求地址
        $sUrl = "https://api.weibo.com/oauth2/authorize?";
        $sUrl .= join("&", $aGet);
        return $sUrl;
    }
}
