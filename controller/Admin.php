<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:11
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberModel;
use think\facade\View;

/**
 * 后台管理
 * Class Admin
 * @package app\member\controller
 */
class Admin extends AdminController
{
    /**
     * 概览页
     * @return string
     */
    public function dashboard(){
        return View::fetch();
    }

    /**
     * 获取数据
     * @return \think\response\Json
     */
    public function getDashboardIndexInfo()
    {
        // 总数
        $totalMember = MemberModel::count();

        // 今日新用户
        $todayNewMember = MemberModel::whereDay('reg_date','today')->count();

        // 7天内增长用户数
        $lastSeventDayNewMember = MemberModel::whereWeek('reg_date','today')->count();

        $adminStatisticsInfo = [
            'total_member' => $totalMember,
            'today_new_member' => $todayNewMember,
            'last_sevent_day_new_member' => $lastSeventDayNewMember,
        ];
        return self::makeJsonReturn(true, ['admin_statistics_info' => $adminStatisticsInfo] );
    }
}
