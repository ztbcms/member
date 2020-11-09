<?php
/**
 * User: cycle_3
 * Date: 2020/11/9
 * Time: 10:56
 */

namespace app\member\controller\api;

use app\BaseController;
use app\member\model\MemberUserModel;
use think\App;
use app\common\util\Encrypt;

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