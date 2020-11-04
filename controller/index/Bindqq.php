<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 16:04
 */

namespace app\member\controller\index;

use app\BaseController;
use app\member\libs\util\Curl;
use app\member\model\MemberConnectTokenModel;
use app\member\model\MemberOpenModel;
use app\member\model\MemberUserModel;
use app\member\service\MemberConnectService;
use app\member\service\MemberUserService;
use liliuwei\think\Jump;
use think\App;

/**
 * QQ空间账号登录
 * Class Bind
 * @package app\member\controller\index
 */
class Bindqq extends BaseController
{
    use Jump;
    protected $app;
    protected $connectMark = MemberOpenModel::TYPE_QQ;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->app = MemberOpenModel::where('app_type', $this->connectMark)->findOrEmpty();
    }

    /**
     * 跳转到授权界面
     */
    public function index()
    {
        //跳转
        $MemberConnectService = new MemberConnectService();
        $url = $MemberConnectService->getUrlConnectQQ();
        if (!$url) {
            $this->error($MemberConnectService->getError());
        }
        header("location:" . $url);
    }

    /**
     * 回调
     */
    public function callback()
    {
        //安全验证，验证state是否合法
        $state = $_GET['state'];
        if ($state != md5('ztb' . request()->ip())) {
            $this->error("IP不正确");
        }
        $sUrl = "https://graph.qq.com/oauth2.0/token";
        $aGetParam = array(
            "grant_type"    => "authorization_code",
            "client_id"     => $this->app->app_key,
            "client_secret" => $this->app->app_secret,
            "code"          => $_GET["code"],
            "state"         => $_GET["state"],
            "redirect_uri"  => session("redirect_uri"),
        );
        session("redirect_uri", NULL);
        //Step2：通过Authorization Code获取Access Token
        $aGet = [];
        foreach ($aGetParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        $Curl = new Curl();
        $sContent = $Curl->get($sUrl . "?" . implode("&", $aGet));
        if ($sContent == FALSE) {
            $this->error("帐号授权出现错误！");
        }
        //参数处理
        $aTemp = explode("&", $sContent);
        $aParam = array();
        foreach ($aTemp as $val) {
            $aTemp2 = explode("=", $val);
            $aParam[$aTemp2[0]] = $aTemp2[1];
        }
        //保存access_token
        session("access_token", $aParam["access_token"]);
        $sUrl = "https://graph.qq.com/oauth2.0/me";
        $aGetParam = array(
            "access_token" => $aParam["access_token"],
        );
        foreach ($aGetParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        $sContent = $Curl->get($sUrl . "?" . implode("&", $aGet));

        if ($sContent == FALSE) {
            $this->error("帐号授权出现错误！");
        }
        $aTemp = array();
        //处理授权成功以后，返回的一串类似：callback( {"client_id":"000","openid":"xxx"} );
        preg_match('/callback\(\s+(.*?)\s+\)/i', $sContent, $aTemp);
        //把json数据转换为数组
        $aResult = json_decode($aTemp[1], true);
        //合并数组，把access_token和expires_in合并。
        $result = array_merge($aResult, $aParam);

        $this->user($result);
    }

    /**
     * 登录/注册
     * @param $Result
     */
    protected function user($Result)
    {
        $openid = $Result['openid'];
        if (!$openid) {
            $this->error("登录失败！");
        }

        $ConnectTokenModel = new MemberConnectTokenModel();
        $uid = $ConnectTokenModel->getUserid($openid, $this->connectMark);
        if ($uid) {
            // 更新操作
            // 更新access_token
            $ConnectTokenModel->updateTokenTime($openid, $this->connectMark, $Result['access_token'], time() + (int)$Result['expires_in']);
            //存在直接登录
            $Member = new MemberUserModel;
            $info = $Member->where('user_id', (int)$uid)->findOrEmpty();
            if ($info) {
                //待审核
                if ($info['checked'] == 0) {
                    $this->error("该帐号还未审核通过，暂无法登录！", api_url('/member/index/index'));
                }
                // TODO  注册用户的登录状态
                if (MemberUserService::registerLogin($info)) {
                    $forward = $_REQUEST['forward'] ? $_REQUEST['forward'] : cookie("forward");
                    redirect($forward ? $forward : api_url("/member/index/index"), 0, '');
                } else {
                    $this->error("登录失败！");
                }
            } else if ($info->isEmpty()) {
                $this->error("用户不存在！", api_url("/member/index/connectregister"));
            } else {
                $this->error("登录失败！", api_url("/member/index/login"));
            }
        } else {
            // 注册
            header("Content-type: text/html; charset=utf-8");
            session("connect_openid", $openid);
            session("connect_expires", time() + (int)$Result['expires_in']);
            session("connect_app", $this->connectMark);
            //不存在，跳转到注册页面
            $this->redirect(api_url("/member/Index/index"));
        }
    }
}
