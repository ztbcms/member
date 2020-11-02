<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 8:58
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberTagModel;
use app\member\validate\MemberTagValidate;

/**
 * 标签管理
 * Class TagService
 * @package app\member\service
 */
class MemberTagService extends BaseService
{
    /**
     * 获取所有标签
     * @param bool $isPage 是否需要分页
     * @param int $limit
     * @param array $where
     * @return \think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getTagsList($isPage = false, $limit = 15, $where = [])
    {
        if($isPage){
            return MemberTagModel::where($where)
                ->order('sort', 'DESC')
                ->paginate($limit);
        }
        return MemberTagModel::where($where)
            ->order('sort', 'DESC')
            ->select();
    }

    /**
     * 获取详情
     * @param $tagId
     * @return array|\think\Model
     */
    static function getTagInfo($tagId)
    {
        return MemberTagModel::where('tag_id', $tagId)->findOrEmpty();
    }


    /**
     * 添加，编辑
     * @param $tagName
     * @param int $tagId
     * @param int $sort
     * @param int $isShow
     * @return boolean
     */
    static function addEditTag($tagName, $tagId = 0, $sort = 0, $isShow = 1)
    {
        $tag = MemberTagModel::where('tag_id', $tagId)->findOrEmpty();
        $tag->tag_name = $tagName;
        $tag->sort = $sort;
        $tag->is_show = $isShow;
        $res = $tag->save();
        if ($res) {
            return $tag->tag_id;
        }
        return false;
    }

    /**
     * 更新某个字段
     * @param $tagId
     * @param $field
     * @param $value
     * @return bool
     */
    static function updateField($tagId, $field, $value)
    {
        $tagInfo = MemberTagModel::where('tag_id', $tagId)->findOrEmpty();
        $tagInfo->$field = $value;
        $res = $tagInfo->save();
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 删除
     * @param $tagId
     * @return array
     */
    static function deleteItem($tagId)
    {
        $tagInfo = MemberTagModel::where('tag_id', $tagId)->findOrEmpty();
        if ($tagInfo->isEmpty()) {
            return self::createReturn(false,[],'标签不存在');
        }
        $res = $tagInfo->delete();
        if ($res){
            return self::createReturn(true,[],'删除成功');
        }
        return self::createReturn(false,[],'删除失败');
    }
}
