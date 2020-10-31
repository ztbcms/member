<?php
/**
 * User: FHYI
 * Date: 2020/10/29
 */

namespace app\member\validate;

use think\Validate;

class MemberValidate extends Validate
{
    protected $rule = [
        'username' => ['require'],
        'password' => ['require'],
        'email'    => ['require'],
    ];

    protected $message = [
        'username.require' => '用户名不能为空！',
        'password.require' => '密码不能为空！',
        'email.require'    => '邮箱不能为空！',
    ];

}
