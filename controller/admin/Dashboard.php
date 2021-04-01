<?php
/**
 * Author: jayinton
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberModel;
use think\facade\View;

class Dashboard extends AdminController
{
    /**
     * 概览页
     *
     * @param  \think\Request  $request
     *
     * @return string
     */
    function index(\think\Request $request)
    {
        $action = $request->get('_action');
        if ($request->isGet() && $action == 'getDashboardIndexInfo') {
            // 总数
            $totalMember = MemberModel::count();

            // 今日新用户
            $todayNewMember = MemberModel::whereDay('reg_time', 'today')->count();

            // 7天内增长用户数
            $lastSeventDayNewMember = MemberModel::whereWeek('reg_time', 'today')->count();

            $adminStatisticsInfo = [
                'total_member'               => $totalMember,
                'today_new_member'           => $todayNewMember,
                'last_sevent_day_new_member' => $lastSeventDayNewMember,
            ];
            return self::makeJsonReturn(true, ['admin_statistics_info' => $adminStatisticsInfo]);
        }
        return View::fetch();
    }

}