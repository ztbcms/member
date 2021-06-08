<?php
/**
 * User: cycle_3
 */

namespace app\member\controller\api;

use app\BaseController;
use think\App;

/**
 * 获取公共基础信息
 * @package app\member\controller\api
 */
class MemberBase extends BaseController
{

    //引入中间件
    protected $middleware = [
        //用户认证
        \app\member\middleware\MemberAuthority::class
    ];

}