<?php
/**
 * User: cycle_3
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