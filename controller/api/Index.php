<?php
/**
 * User: cycle_3
 */

namespace app\member\controller\api;

use app\Request;
use Firebase\JWT\JWT;

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

    function test(){
        $res = JWT::encode(['user_id' => 1, 'name' => 'jayin'], 'ztbcms');
        var_dump($res);
    }


}