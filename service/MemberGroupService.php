<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 10:10
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberGroupModel;
use app\member\model\MemberUserModel;

/**
 * 用户组管理
 * Class MemberGroupService
 * @package app\member\service
 */
class MemberGroupService extends BaseService
{
    /**
     * 获取所有用户组
     * @param bool $isPage 是否需要分页
     * @param int $limit
     * @param array $where
     * @return \think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getGroupList($isPage = false, $limit = 15, $where = [])
    {
        if ($isPage) {
            return MemberGroupModel::where($where)
                ->order('sort', 'DESC')
                ->paginate($limit);
        }
        return MemberGroupModel::where($where)
            ->order('sort', 'DESC')
            ->select();
    }

    /**
     * 获取会员组中的用户数
     * @param $groupId
     * @return int
     */
    static function getUserCountByGroupId($groupId)
    {
        return MemberUserModel::where('group_id', $groupId)->count();
    }

    /**
     * 获取详情
     * @param $groupId
     * @return array|\think\Model
     */
    static function getGroupInfo($groupId)
    {
        return MemberGroupModel::where('group_id', $groupId)->findOrEmpty();
    }


    /**
     * 添加，编辑
     * @return boolean
     */
    static function addEditGroup($data)
    {
        $groupId = !empty($data['group_id']) ? $data['group_id'] : 0;
        $group = MemberGroupModel::where('group_id', $groupId)->findOrEmpty();
        // 权限处理
        if (!empty($data['power'])) {
            foreach ($data['power'] as $item) {
                $data = array_merge($data, [$item => 1]);
            }
            $data['power'] = serialize($data['power']);
        }
        // 扩展
        $data['expand'] = serialize($data['expand']);
        $res = $group->save($data);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 更新某个字段
     * @param $groupId
     * @param $field
     * @param $value
     * @return bool
     */
    static function updateField($groupId, $field, $value)
    {
        $groupInfo = MemberGroupModel::where('group_id', $groupId)->findOrEmpty();
        $groupInfo->$field = $value;
        $res = $groupInfo->save();
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 批量删除
     * @param $groupIds
     * @return array
     */
    static function deleteItem($groupIds)
    {
        $count = 0;
        foreach ($groupIds as $groupId) {
            $groupInfo = MemberGroupModel::where('group_id', $groupId)->findOrEmpty();
            $count += $groupInfo->delete();
        }
        if ($count > 0) {
            return self::createReturn(true, [], '删除成功');
        }
        return self::createReturn(false, [], '删除失败');
    }

    /**
     * 更新排序
     * @param $data
     * @return bool
     */
    static function listOrder($data)
    {
        $MemberGroupModel = new MemberGroupModel();
        $res = $MemberGroupModel->transaction(function () use ($data) {
            foreach ($data as $item) {
                MemberGroupModel::where('group_id', $item['group_id'])
                    ->save(['sort' => $item['sort']]);
            }
            return true;
        });
        if ($res) return true;
        return false;
    }
}
