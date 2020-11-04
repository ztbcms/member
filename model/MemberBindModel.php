<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 9:08
 */

namespace app\member\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 用户绑定第三方
 * Class MemberBindModel
 * @package app\member\model
 */
class MemberBindModel extends Model
{
    protected $name = 'member_bind';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $append = ['app_name'];

    /**
     * 获取应用名称
     * @param $val
     * @param $data
     * @return mixed
     */
    public function getAppNameAttr($val, $data)
    {
        $MemberOpenModel = new MemberOpenModel();
        return $MemberOpenModel->appNames[$data['bind_type']];
    }
}
