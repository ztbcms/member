<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\admin;

use app\admin\service\AdminUserService;
use app\common\controller\AdminController;
use app\member\model\record\records\IntegrationRecord;
use app\member\model\record\records\TradeRecord;

/**
 * 记录管理
 * Class Records
 * @package app\member\controller\admin
 */
class Records extends AdminController
{

    /**
     * 记录变动
     * @return array|\think\response\View
     */
    public function change()
    {
        $_action = input('_action', '', 'trim');
        if ($_action == 'submit') {
            $adminInfo = (new AdminUserService)->getInfo();
            $model = input('model', '', 'trim');
            $type = input('type', '', 'trim');
            if ($type == 'income') {
                $types = IntegrationRecord::INCREASE;
            } else {
                $types = IntegrationRecord::PAY;
            }
            $to = input('user_id', '0', 'trim');
            $amount = input('val', '0', 'trim');
            $remark = input('remark', '', 'trim');
            if ($model == 'integration') {
                //积分管理
                $IntegrationRecord = new IntegrationRecord(
                    $types, $to, 'user_id',
                    '', '',
                    $adminInfo['id'], 'admin_id',
                    $amount, $remark
                );
                $IntegrationRecord->createRrcord();
            } else {
                if ($model == 'trade') {
                    //余额管理
                    $TradeRecord = new TradeRecord(
                        $types, $to, 'user_id',
                        '', '',
                        $adminInfo['id'], 'admin_id',
                        $amount, $remark
                    );
                    $TradeRecord->createRrcord();
                }
            }
            return self::createReturn(true, '', '操作成功');
        }
        return view();
    }

    /**
     * 记录列表
     * @return \think\response\Json|\think\response\View
     */
    public function log()
    {
        $_action = input('_action', '', 'trim');
        if ($_action == 'list') {
            $model = input('model', '', 'trim');
            $user_id = input('user_id', '', 'trim');
            $list = [];
            if ($model == 'integration') {
                //积分管理
                $list = (new IntegrationRecord(
                    '', $user_id, 'user_id'
                ))->log(input('limit'));
            } else {
                if ($model == 'trade') {
                    //余额记录
                    $list = (new TradeRecord(
                        '', $user_id, 'user_id'))
                        ->log(input('limit'));
                }
            }
            return json(self::createReturn(true, $list));
        }
        return view();
    }

}