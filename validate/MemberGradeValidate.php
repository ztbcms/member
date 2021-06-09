<?php
/**
 * Author: cycle_3
 */

namespace app\member\validate;

use think\Validate;

/**
 * 会员等级
 * Class MemberGradeValidate
 * @package app\member\validate
 */
class MemberGradeValidate extends Validate
{

    protected $rule = [
        'member_grade_name' => 'require',
        'meet_integration'  => 'number',
        'meet_trade'        => 'number',
    ];

    protected $message = [
        'member_grade_name.require' => '抱歉，等级名称不能为空',
        'meet_integration.number'   => '抱歉，积分只能填写数字',
        'meet_trade.number'         => '抱歉，余额只能填写数字',
    ];

    protected $scene = [
        'submit' => [
            'member_grade_name', 'meet_integration', 'meet_trade'
        ],
    ];

}