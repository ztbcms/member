<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 8:58
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberBindModel;
use app\member\model\MemberConnectTokenModel;
use app\member\model\MemberTagModel;
use app\member\validate\MemberTagValidate;

/**
 * 第三方授权管理
 * Class MemberConnectService
 * @package app\member\service
 */
class MemberConnectService extends BaseService
{
    /**
     * 获取授权记录token
     * @param bool $isPage 是否需要分页
     * @param int $limit
     * @param array $where
     * @return \think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getTokenList($isPage = false, $limit = 15, $where = [])
    {
        if($isPage){
            return MemberConnectTokenModel::with('userName')->where($where)->paginate($limit);
        }
        return MemberConnectTokenModel::with('userName')->where($where)->select();
    }

    /**
     * 通过第三方凭证 获取系统用户id
     * @param $openId
     * @return array|\think\Model
     */
    static function getUid($openId)
    {
        return MemberBindModel::where('bind_open_id',$openId)->value('user_id');
    }

    /**
     * 删除token 记录
     * @param $userId
     * @param $bindType
     * @return array
     */
    static function unBindUser($userId,$bindType)
    {
        $bind = MemberBindModel::where('user_id', $userId)
            ->where('bind_type',$bindType)
            ->findOrEmpty();
        if ($bind->isEmpty()) {
            return self::createReturn(false,[],'绑定关系不存在');
        }
        $res = $bind->delete();
        if ($res){
            return self::createReturn(true,[],'解绑成功');
        }
        return self::createReturn(false,[],'解绑失败');
    }

    /**
     * 删除token 记录
     * @param $tokenId
     * @return array
     */
    public function deleteToken($tokenId)
    {
        $token = MemberConnectTokenModel::where('token_id', $tokenId)->findOrEmpty();
        if ($token->isEmpty()) {
            return self::createReturn(false,[],'token不存在');
        }
        $res = $token->delete();
        if ($res){
            return self::createReturn(true,[],'删除成功');
        }
        return self::createReturn(false,[],'删除失败');
    }
}
