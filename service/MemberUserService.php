<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 15:29
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberUserModel;

/**
 * 会员
 * Class MemberUserService
 * @package app\member\service
 */
class MemberUserService extends BaseService
{
    /**
     * 获取用户列表
     * @param array $where
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    static function getList($where = [], $limit = 15)
    {
        return MemberUserModel::where($where)
            ->order('create_time', 'desc')
            ->paginate($limit);
    }

    static function getDetail($userId){
        $user = MemberUserModel::where('user_id',$userId)->findOrEmpty();
        if(!$user->isEmpty()){
            // 查询标签
            $user['tag_ids'] = MemberTagBindService::getUserTagIds($userId);
        }
        return $user;
    }
}
