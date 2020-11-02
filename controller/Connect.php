<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 18:17
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use think\facade\View;

/**
 * 第三方接入
 * Class Connect
 * @package app\member\controller
 */
class Connect extends AdminController
{
    public function lists()
    {
        return View::fetch();
    }

}
