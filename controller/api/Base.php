<?php
/**
 * User: cycle_3
 */

namespace app\member\controller\api;

use app\BaseController;
use think\App;

/**
 * 获取公共基础信息
 * Class Base
 * @package app\member\controller\api
 */
class Base extends BaseController
{

    //引入中间件
    protected $middleware = [
        //权限控制
        \app\member\middleware\Authority::class
    ];


    /**
     * 初始化
     */
    protected function initialize()
    {
        parent::initialize();
    }




}