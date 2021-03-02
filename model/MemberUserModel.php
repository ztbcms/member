<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:14
 */

namespace app\member\model;

use app\cms\model\ModelModel;
use think\facade\Db;
use think\Model;

/**
 * 会员
 * Class MemberUserModel
 * @package app\member\model
 */
class MemberUserModel extends Model
{
    protected $name = 'member';
    protected $pk = 'user_id';

    // 已审核
    const IS_CHECKED = 1;
    // 拉黑
    const IS_BLOCK = 1;
    // 取消审核
    const NO_CHECKED = 0;
    // 取消拉黑
    const NO_BLOCK = 0;

    protected $append = [
        'tags_name'
    ];

    /**
     * 获取用户标签名称
     * @param $val
     * @param $data
     * @return string
     */
    public function getTagsNameAttr($val, $data)
    {
        $tagIds = MemberTagBindModel::where('user_id', $data['user_id'])->column('tag_id');
        $tagName = MemberTagModel::whereIn('tag_id', $tagIds)->column('tag_name');
        return implode(',',$tagName);
    }

    /**
     * 会员配置缓存
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function member_cache() {
        $data = Db::name('module')->where('module','Member')->value('setting');
        $setting = unserialize($data);
        cache("Member_Config", $setting);
        $this->member_model_cahce();
        return $data;
    }

    /**
     * 会员模型缓存
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function member_model_cahce() {
        $data = ModelModel::getModelAll(2);
        cache("Model_Member", $data);
        return $data;
    }

}
