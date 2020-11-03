<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 16:12
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberTagBindModel;

/**
 * 标签绑定
 * Class MemberTagBindService
 * @package app\member\service
 */
class MemberTagBindService extends BaseService
{
    /**
     * 绑定用户标签
     * @param $userId
     * @param $tagIds
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function bindUserTag($userId, $tagIds)
    {
        // 删除原有绑定
        $MemberTagBindModel = new MemberTagBindModel();
        $MemberTagBindModel->where('user_id', $userId)->select()->delete();
        if(empty($tagIds)){
            return true;
        }

        $data = [];
        foreach ($tagIds as $tagId) {
            $data[] = [
                'user_id' => $userId,
                'tag_id'  => $tagId,
            ];
        }
        return $MemberTagBindModel->saveAll($data);
    }

    static function getUserTagIds($userId){
        return MemberTagBindModel::where('user_id', $userId)->column('tag_id');
    }
}
