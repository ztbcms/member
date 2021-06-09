<?php
/**
 * Author: cycle_3
 */

namespace app\member\cronscript;

use app\common\cronscript\CronScript;
use app\member\model\MemberGradeModel;
use app\member\model\MemberModel;

/**
 * 自动同步用户等级 （建议间隔时间10分钟）
 * Class AutoSysMemberGradeScript
 * @package app\member\cronscript
 */
class AutoSysMemberGradeScript extends CronScript
{

    public function run($cronId)
    {
        $MemberModel = new MemberModel();
        $MemberGradeModel = new MemberGradeModel();
        $member = $MemberModel
            ->where('update_time', '<', time() - 60 * 10)
            ->column('user_id');
        foreach ($member as $k => $v) {
            $MemberGradeModel->sysMemberGrade($v);
        }
        return true;
    }

}