<?php
/**
 * Author: cycle_3
 */

namespace app\member\validate;

use think\Validate;

/**
 * 会员管理
 * Class MemberValidate
 * @package app\member\validate
 */
class MemberValidate extends Validate
{

    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'source' => 'require',
        'source_type' => 'require',
    ];

    protected $message = [
        'username.require' => '抱歉，账号不能为空',
        'password.require' => '抱歉，密码不能为空',
        'source.require' => '抱歉，来源不能为空',
        'source_type.require' => '抱歉，来源不能为空',
    ];

    protected $scene = [
        'register' => [
            'username', 'password', 'source', 'source_type'
        ],
        'login' => [
            'username', 'password'
        ]
    ];

}