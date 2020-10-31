<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 17:58
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberGroupModel;

/**
 * 会员组
 * Class Group
 * @package app\member\controller
 */
class Group extends AdminController
{
    public function getGroupList()
    {
        $list = MemberGroupModel::column('groupid,name');
        return self::makeJsonReturn(true, $list);
    }
}
