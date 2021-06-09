<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberConfigModel;

/**
 * 会员配置
 * Class Config
 * @package app\member\controller\admin
 */
class Config extends AdminController
{

    /**
     * 配置管理
     * @return \think\response\Json|\think\response\View
     */
    function index()
    {
        $_action = input('_action', '', 'trim');
        if ($_action == 'details') {
            //获取详情
            $MemberConfigModel = new MemberConfigModel();
            $details = $MemberConfigModel->getDetails();
            return json($details);
        } else {
            if ($_action == 'submit') {
                //提交内容
                $post = input('post.');
                $MemberConfigModel = new MemberConfigModel();
                return json($MemberConfigModel->submit($post));
            }
        }
        return view();
    }

}