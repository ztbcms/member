<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 9:27
 */

namespace app\member\validate;

use think\Validate;

/**
 * 用户组验证器
 * Class MemberGroupValidate
 * @package app\member\validate
 */
class MemberGroupValidate extends Validate
{
    protected $rule = [
        'group_name' => 'require',
    ];

    protected $message = [
        'group_name.require' => '会员组名不能为空！'
    ];
}
