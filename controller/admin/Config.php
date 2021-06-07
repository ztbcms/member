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


    public function index()
    {
        $_action = input('_action', '', 'trim');
        if($_action == 'details') {
            //获取详情
            $MemberConfigModel = new MemberConfigModel();
            $details = $MemberConfigModel->getDetails();
            return json(createReturn(true,$details));
        } else if($_action == 'submit'){
            //提交内容
            $post = input('post.');
            $MemberConfigModel = new MemberConfigModel();
            $MemberConfigModel->submit($post);
            return json(createReturn(true));
        }
        return view();
    }

}