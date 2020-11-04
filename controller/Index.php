<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:06
 */

namespace app\member\controller;

use app\admin\service\AdminConfigService;
use app\BaseController;
use liliuwei\think\Jump;
use think\App;
use think\facade\Event;
use think\facade\View;

/**
 * 前台 TODO
 * Class Index
 * @package app\member\controller
 */
class Index extends BaseController
{
    use Jump;
    protected $noNeedPermission = ['index'];

    protected $userId = 0;

    public function __construct(App $app)
    {
        parent::__construct($app);
        // 检查用户是否登录 TODO
    }


    public function test()
    {
        // 直接使用事件类触发
        Event::trigger('app\member\event\Test');
        event('app\member\event\Test');

    }

    public function index()
    {
        return View::fetch();
    }

    //登录页面
    public function login()
    {
        $forward = cookie("forward");
        if (!empty($_REQUEST['forward'])) {
            $forward = $_REQUEST['forward'];
        }
        cookie("forward", null);
        if (!empty($this->userid)) {
            $this->success("您已经是登录状态！", $forward ? $forward : U("Index/index"));
        } else {
            // TODO 目前写死指定模板， 自由选择模板 TODO
            $AdminConfigService = new AdminConfigService();
            $config = $AdminConfigService->getConfig()['data'];
            View::assign('Config', $config);
            View::assign('model_extresdir', '/statics/extres/member/');
            return View::fetch('template/public/login', compact('forward'));
        }
    }
}
