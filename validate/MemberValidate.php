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
        'username'         => 'require',
        'password'         => 'require',
        'password_confirm' => 'requireCallback:checkPasswordData|confirm:password',
        'source'           => 'require',
        'source_type'      => 'require',
        'nickname'         => 'require',
        'role_id'          => 'require',
    ];

    protected $message = [
        'username.require'                 => '账号不能为空',
        'password.require'                 => '密码不能为空',
        'password_confirm.requireCallback' => '确认密码不能为空',
        'password_confirm.confirm'         => '确认和密码不一致',
        'source.require'                   => '来源不能为空',
        'source_type.require'              => '来源不能为空',
        'nickname.require'                 => '用户昵称不能为空',
        'role_id.require'                  => '用户角色不能为空',
    ];

    protected $scene = [
        'register'        => [
            'username', 'password', 'source', 'source_type'
        ],
        'login'           => [
            'username', 'password'
        ],
        'add_admin_user'  => [
            'username', 'password', 'password_confirm', 'source', 'source_type', 'nickname', 'role_id'
        ],
        'edit_admin_user' => [
            'username', 'source', 'password_confirm', 'source_type', 'nickname', 'role_id'
        ],
        'reset_password' => [
            'username', 'password'
        ]
    ];

    /**
     * 判断是否需要校验确认密码
     * @param $value
     * @param  array  $data
     * @return bool
     */
    protected function checkPasswordData($value, $data = [])
    {
        if (isset($data['password']) && !empty($data['password'])) {
            return true;
        } else {
            return false;
        }
    }

}