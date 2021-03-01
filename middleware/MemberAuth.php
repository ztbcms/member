<?php
/**
 * Author: jayinton
 */

namespace app\member\middleware;

use app\common\service\BaseService;
use app\member\libs\ReturnCode;
use app\member\model\MemberUserModel;
use app\member\service\TokenService;
use think\Request;

/**
 * Class MemberAuth
 *
 * @package app\member\middleware
 */
class MemberAuth
{
    /**
     * 进入请求
     *
     * @param $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // 是否忽略授权
        $ignore_auth = false;
        $noNeedAuth = $request->noNeedAuth ?? [];
        if ($this->_checkActionMatch($request->action(), $noNeedAuth)) {
            $ignore_auth = true;
        }
        $memberRes = [
            'userId'   => null,
            'userInfo' => null
        ];
        if (!$ignore_auth) {
            $token = $request->header('token', '');
            if ($token) {
                $user_id = TokenService::decode($token);
                if (empty($user_id)) {
                    return json(BaseService::createReturn(false, null, '请登录', ReturnCode::NO_LOGIN));
                }
                $MemberUserModel = new MemberUserModel();
                $memberFind = $MemberUserModel->where('user_id', $user_id)->find();
                if (empty($memberFind)) {
                    return json(BaseService::createReturn(false, null, '找不到用户', ReturnCode::NO_LOGIN));
                }
                $member = $memberFind->toArray();
                unset($member['password']);
                unset($member['encrypt']);
                $memberRes['userInfo'] = $memberFind;
                $memberRes['userId'] = $memberFind['user_id'];
            }
        }
        $request->userId = $memberRes['userId'];
        // TODO 建议删除，仅保留userId
        $request->userInfo = $memberRes['userInfo'];
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
     * 检测控制器的方法是否匹配
     *
     * @param $action
     * @param $arr
     *
     * @return bool
     */
    function _checkActionMatch($action, array $arr)
    {
        if (empty($arr)) {
            return false;
        }

        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($action), $arr) || in_array('*', $arr)) {
            return true;
        }

        // 没找到匹配
        return false;
    }
}