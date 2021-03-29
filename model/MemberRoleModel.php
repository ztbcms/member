<?php
/**
 * Author: jayinton
 */

namespace app\member\model;


use think\Model;

class MemberRoleModel extends Model
{
    protected $name = 'member_role';

    // 启用状态
    /**
     * 启用状态：启用
     */
    const STATUS_YES = 1;
    /**
     * 启用状态：禁用
     */
    const STATUS_NO = 0;
}