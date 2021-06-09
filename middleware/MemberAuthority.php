<?php
/**
 * User: Cycle3
 */

namespace app\member\middleware;

use app\member\model\MemberConfigModel;
use app\member\model\MemberModel;
use app\member\libs\ReturnCode;
use app\member\model\MemberTokenModel;

/**
 * 权限控制中间件
 * Class OperationLog
 * @package app\member\middleware
 */
class MemberAuthority
{

    /**
     * 进入请求
     * @param $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $memberRes = $this->getTokenInfo();
        $jurisdiction_array = [
            '0' => 'member/api.home/sysMemberGrade'
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
                return json(createReturn(false, [], '抱歉，您需要进行登录操作', ReturnCode::NO_LOGIN));
            }

            $MemberConfigModel = new MemberConfigModel();
            $block_switch = $MemberConfigModel->getMembefConfig('block_switch')['data'];
            if ($block_switch == 1) {
                //开启了审核按钮
                return json(createReturn(false, [], '抱歉，您已经被后台拉黑了', ReturnCode::YES_BLOCK));
            }

            $audit_switch = $MemberConfigModel->getMembefConfig('audit_switch')['data'];
            if ($audit_switch == 1) {
                //开启了审核按钮
                return json(createReturn(false, [], '抱歉，您暂未通过审核', ReturnCode::NO_AUDIT));
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
        if ($token) {
            $getUserId = (new MemberTokenModel())->decodeToken($token);
            if ($getUserId) {
                $MemberModel = new MemberModel();
                $memberFind = $MemberModel->where('user_id', $getUserId)->findOrEmpty();
                if (!$memberFind->isEmpty()) {
                    $res['userInfo'] = $memberFind;
                    $res['userId'] = $memberFind['user_id'];
                }
            }
        }
        return $res;
    }
}