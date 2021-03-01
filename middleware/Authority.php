<?php
/**
 * User: Cycle3
 */

namespace app\member\middleware;

use app\member\libs\util\Encrypt;
use app\member\model\MemberUserModel;
use app\BaseController;
use app\member\libs\ReturnCode;
/**
 * 权限控制中间件
 * @deprecated 
 * Class OperationLog
 * @package app\member\middleware
 */
class Authority
{
    /**
     * 进入请求
     * @param $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $memberRes = self::getTokenInfo();

        $jurisdiction_array = [
            '0' => '/home/member/api.index/index',
        ];
        $AuthorizationCode = false;
        foreach ($jurisdiction_array as $key => $val) {
            if (strpos($_SERVER['REQUEST_URI'], $val) !== false) {
                $AuthorizationCode = true;
            }
        }

        if (!$AuthorizationCode) {
            if (!$memberRes['userId']) {
                //未进行登录操作
                return BaseController::makeJsonReturn(false, [], '对不起，您需要进行登录操作',ReturnCode::NO_LOGIN);
            }
        }

        $request->userId = $memberRes['userId'];
        $request->userInfo = $memberRes['userInfo'];
        return $next($request);
    }

    /**
     * 请求返回回调
     * @param  \think\Response  $response
     */
    public function end(\think\Response $response)
    {

    }


    /**
     * 获取用户token信息
     * @return mixed
     */
    public static function getTokenInfo()
    {
        $headerKey = 'HTTP_ZTBTOKEN';
        $token = isset($_SERVER[$headerKey]) ? $_SERVER[$headerKey] : '';
        $res['userInfo'] = [];
        $res['userId'] = 0;
        if($token) {
            $getUserId = Encrypt::authcode($token, Encrypt::OPERATION_DECODE,'ZTBCMS');
            if($getUserId) {
                $MemberUserModel = new MemberUserModel();
                $memberFind = $MemberUserModel->where('user_id',$getUserId)->find();
                if(!empty($memberFind)) {
                    $res['userInfo'] = $memberFind;
                    $res['userId'] = $memberFind['user_id'];
                }
            }
        }
        return $res;
    }
}