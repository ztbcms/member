<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\api;

use app\Request;

/**
 * demo
 * Class Home
 * @package app\member\controller\api
 */
class Home extends WeChatBase
{

    /**
     * 获取用户信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function user(Request $request)
    {
        return json(self::createReturn(true, [
            'user_id' => $request->userId,
            'user_info' => $request->userInfo
        ]));
    }

}