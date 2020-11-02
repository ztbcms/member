<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 9:27
 */

namespace app\member\validate;

use think\Validate;

/**
 * 标签验证器
 * Class MemberTagValidate
 * @package app\member\validate
 */
class MemberTagValidate extends Validate
{
    protected $rule = [
        'tag_name' => 'require',
    ];

    protected $message = [
        'tag_name.require' => '标签名不能为空！'
    ];
}
