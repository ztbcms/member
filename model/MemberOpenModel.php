<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 9:35
 */

namespace app\member\model;

use think\Model;

/**
 * 第三方平台接入管理
 * Class MemberOpenModel
 * @package app\member\model
 */
class MemberOpenModel extends Model
{
    protected $name = 'member_open';
    protected $append = ['app_name'];

    const TYPE_QQ = 'qq';
    const TYPE_WEIBO = 'weibo';

    // 获取类型名称
    public function getAppNameAttr($val, $data)
    {
        $names = [
            'qq'    => 'QQ',
            'weibo' => '新浪微博',
        ];
        return $names[$data['app_type']];
    }
}
