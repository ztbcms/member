<?php
/**
 * Author: cycle_3
 */

namespace app\member\model;

use think\Model;
use think\model\concern\SoftDelete;

class MemberConfigModel extends Model
{

    use SoftDelete;

    protected $name = 'member_config';
    protected $pk = 'member_config_id';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 获取详情
     * @return array
     */
    public function getDetails()
    {
        $list = $this->select();
        $details = [];
        foreach ($list as $k => $v) {
            $details[$v->info] = $v->value;
        }
        return createReturn(true, $details);
    }


    /**
     * 保存配置
     * @param  array  $post
     * @return array
     */
    public function submit($post = [])
    {
        unset($post['_action']);
        foreach ($post as $k => $v) {
            $details = $this->where('info', '=', $k)->findOrEmpty();
            if ($details->isEmpty()) {
                $details->create_time = time();
            }
            $details->info = $k;
            $details->value = $v;
            $details->update_time = time();
            $details->save();
        }
        return createReturn(true);
    }


    /**
     * 获取配置
     * @param  string  $info
     * @return array
     */
    public function getMembefConfig($info = '')
    {
        $value = $this
            ->where('info', '=', $info)
            ->value('value') ?: '';
        return createReturn(true, $value);
    }

}