<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 18:22
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use think\facade\View;

/**
 * 会员模型
 * Class Model
 * @package app\member\controller
 */
class Model extends AdminController
{
    public function lists()
    {
        return View::fetch();
    }
}
