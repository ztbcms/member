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
 *
 * @package app\member\middleware
 */
class MemberAuthority
{

    /**
     * 进入请求
     *
     * @param $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $userId = $this->getTokenInfo();
        if (!$userId) {
            //未进行登录操作
            return json(createReturn(false, [], '抱歉，您需要进行登录操作', ReturnCode::NO_LOGIN));
        }
        $member = MemberModel::where('user_id', $userId)->field('user_id,audit_status,sex,nickname,is_block,role_id,')->findOrEmpty();
        if ($member->isEmpty()) {
            return json(createReturn(false, [], '找不到用户', ReturnCode::NO_LOGIN));
        }
        $block_switch = MemberConfigModel::getMembefConfig('block_switch')['data'];
        if ($block_switch == 1 && $member['is_block'] == MemberModel::IS_BLOCK_YES) {
            return json(createReturn(false, [], '抱歉，您已经被后台拉黑了', ReturnCode::YES_BLOCK));
        }
        $audit_switch = MemberConfigModel::getMembefConfig('audit_switch')['data'];
        if ($audit_switch == 1 && $member['audit_status'] != MemberModel::AUDIT_STATUS_PASS) {
            return json(createReturn(false, [], '抱歉，您暂未通过审核', ReturnCode::NO_AUDIT));
        }
        $request->userId = $userId;
        $request->userInfo = $member->toArray();
        return $next($request);
    }

    /**
     * 请求返回回调
     *
     * @param  \think\Response  $response
     */
    public function end(\think\Response $response)
    {

    }


    /**
     * 获取用户token信息
     *
     * @return mixed
     */
    public static function getTokenInfo()
    {
        $headerKey = 'HTTP_ZTBTOKEN';
        $token = isset($_SERVER[$headerKey]) ? $_SERVER[$headerKey] : '';

        if ($token) {
            $userId = MemberTokenModel::getUserIdByToken($token);
            return $userId;
        }
        return null;
    }
}