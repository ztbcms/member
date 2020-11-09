<?php
/**
 * User: cycle_3
 * Date: 2020/11/9
 * Time: 10:56
 */

namespace app\member\controller\api;

use app\Request;

/**
 * demo
 * Class Index
 * @package app\member\controller\api
 */
class Index extends Base
{

    /**
     * 获取首页信息
     * @return \think\response\Json
     */
    public function index(Request $request){
        return json(self::createReturn(true,[
            'userInfo' => $request->userInfo
        ]));
    }


}