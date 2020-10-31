<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:06
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use think\facade\View;

/**
 * 前台 TODO
 * Class Index
 * @package app\member\controller
 */
class Index extends AdminController
{
    public function index()
    {
        return View::fetch();
    }
}
