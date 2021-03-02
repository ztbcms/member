<?php
/**
 * Author: jayinton
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberUserModel;
use think\facade\View;

class Dashboard extends AdminController
{
    public $noNeedPermission = ['getDashboardIndexInfo'];

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
            $totalMember = MemberUserModel::count();

            // 今日新用户
            $todayNewMember = MemberUserModel::whereDay('reg_date', 'today')->count();

            // 7天内增长用户数
            $lastSeventDayNewMember = MemberUserModel::whereWeek('reg_date', 'today')->count();

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