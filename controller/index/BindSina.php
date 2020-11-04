<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 16:58
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
 * 绑定新浪微博
 * Class BindSina
 * @package app\member\controller\index
 */
class BindSina extends BaseController
{
    use Jump;
    protected $app;
    protected $connectMark = MemberOpenModel::TYPE_WEIBO;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->app = MemberOpenModel::where('app_type', $this->connectMark )->findOrEmpty();
    }

    /**
     * 跳转到授权界面
     */
    public function index()
    {
        //跳转
        $MemberConnectService = new MemberConnectService();
        $url = $MemberConnectService->getUrlConnectSinaWeibo();
        if (!$url) {
            echo $MemberConnectService->getError();die();
        }
        header("location:" . $url);
    }

    /**
     * 回调
     */
    public function callback() {
        //安全验证，验证state是否合法
        $state = $_GET['state'];
        if ($state != md5('ztb' . request()->ip())) {
            $this->error("IP不正确");
        }
        $curl = new Curl();
        $sUrl = "https://api.weibo.com/oauth2/access_token";
        $aGetParam = array(
            "code" => $_GET["code"], //用于调用access_token，接口获取授权后的access token
            "client_id" => $this->app->app_key, //申请应用时分配的AppSecret
            "client_secret" => $this->app->app_secret, //申请应用时分配的AppSecret
            "grant_type" => "authorization_code", //请求的类型，可以为authorization_code、password、refresh_token。
            "redirect_uri" => session("redirect_uri"), //回调地址
        );
        session("redirect_uri", NULL);

        $sContent = $curl->post($sUrl, $aGetParam);
        if ($sContent == FALSE) {
            $this->error("帐号授权出现错误！");
        }
        //参数处理
        $aParam = json_decode($sContent, true);
        //保存access_token
        session("access_token", $aParam["access_token"]);

        //新浪微博没有类似腾讯还需取得openid，直接以新浪uid作为标识
        $this->user($aParam);
    }

    /**
     * 登录/注册
     * @param  $result
     */
    protected function user($result) {
        $openid = $result['uid'];
        if (!$openid) {
            $this->error("授权失败！", U("Connectsina/index"));
        }

        $ConnectTokenModel = new MemberConnectTokenModel();
        $uid = $ConnectTokenModel->getUserid($openid, $this->connectMark);
        if ($uid) {
            //更新access_token
            $ConnectTokenModel->updateTokenTime($openid, $this->connectMark, $result['access_token'], time() + (int)$result['expires_in']);
            //存在直接登录
            $Member = new MemberUserModel;
            $info = $Member->where('user_id', (int)$uid)->findOrEmpty();
            if ($info) {
                //待审核
                if ($info['checked'] == 0) {
                    $this->error("该帐号还未审核通过，暂无法登录！", api_url("Member/Index/login"));
                }

                if (MemberUserService::registerLogin($info)) {
                    $forward = $_REQUEST['forward'] ? $_REQUEST['forward'] : cookie("forward");
                    redirect($forward ? $forward : api_url("/member/Index/index"), 0, '');
                } else {
                    $this->error("登录失败！", api_url("/member/Index/login"));
                }
            } else if ($info->isEmpty()) {
                $this->error("用户不存在！", api_url("/member/Public/connectregister"));
            } else {
                $this->error("登录失败！", api_url("/member/Index/login"));
            }
        } else {
            header("Content-type: text/html; charset=utf-8");
            session("connect_openid", $openid);
            session("connect_expires", time() + (int) $result['expires_in']);
            session("connect_app", $this->connectMark);
            //不存在，跳转到注册页面
            $this->redirect(api_url("/member/Public/connectregister"));
        }
    }
}
