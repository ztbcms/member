<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\api;

use app\member\model\record\records\IntegrationRecord;
use app\member\model\record\records\TradeRecord;
use app\Request;

/**
 * demo
 * Class Home
 * @package app\member\controller\api
 */
class Member extends MemberBase
{

    /**
     * 获取用户信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function user(Request $request)
    {
        return json(self::createReturn(true, [
            'user_id' => $request->userId,
            'user_info' => $request->userInfo
        ]));
    }

    /**
     * 添加用户余额
     * @return \think\response\Json
     */
    public function incomeTrade()
    {
        $user_id = input('user_id');
        //模拟获取推荐用户下单奖励
        $TradeRecord = new TradeRecord(
            TradeRecord::INCREASE,
            $user_id, 'user_id',
            '123456', 'order_sn',
            '2', 'recommend_user_id',
            '500', '推荐用户下单奖励'
        );
        return json(self::createReturn(true,$TradeRecord->createRrcord()));
    }

    /**
     * 减少用户余额
     * @return \think\response\Json
     */
    public function payTrade()
    {
        $user_id = input('user_id');
        //模拟获取推荐用户下单奖励
        $TradeRecord = new TradeRecord(
            TradeRecord::PAY,
            $user_id, 'user_id',
            '789789', 'order_sn',
            '', '',
            '10', '购物商品订单'
        );
        return json(self::createReturn(true,$TradeRecord->createRrcord()));
    }

    /**
     * 获取用户余额
     * @return \think\response\Json
     */
    public function tradeBalance()
    {
        $user_id = input('user_id');
        $TradeRecord = new TradeRecord(
            '', $user_id, 'user_id'
        );
        return json(self::createReturn(true,
            [
                'balance' => $TradeRecord->balance()
            ]
        ));
    }

    /**
     * 添加用户积分
     * @return \think\response\Json
     */
    public function incomeIntegration()
    {
        $user_id = input('user_id');
        //模拟获取推荐用户下单奖励
        $IntegrationRecord = new IntegrationRecord(
            TradeRecord::INCREASE,
            $user_id, 'user_id',
            '123456', 'order_sn',
            '2', 'recommend_user_id',
            '500', '推荐用户下单奖励'
        );
        return json(self::createReturn(true,$IntegrationRecord->createRrcord()));
    }

    /**
     * 减少用户积分
     * @return \think\response\Json
     */
    public function payIntegration()
    {
        $user_id = input('user_id');
        //模拟获取推荐用户下单奖励
        $IntegrationRecord = new IntegrationRecord(
            TradeRecord::PAY,
            $user_id, 'user_id',
            '789789', 'order_sn',
            '', '',
            '10', '购物商品订单'
        );
        return json(self::createReturn(true,$IntegrationRecord->createRrcord()));
    }

    /**
     * 获取用户积分
     * @return \think\response\Json
     */
    public function tradeIntegration()
    {
        $user_id = input('user_id');
        $IntegrationRecord = new IntegrationRecord(
            '', $user_id, 'user_id'
        );
        return json(self::createReturn(true,
            [
                'balance' => $IntegrationRecord->balance()
            ]
        ));
    }

    /**
     * 同步用户等级
     * @return \think\response\Json
     */
    public function sysMemberGrade()
    {
        return json((new \app\member\model\MemberGradeModel())->sysMemberGrade(input('user_id')));
    }
}