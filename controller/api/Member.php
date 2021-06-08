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
        //模拟获取推荐用户下单奖励
        $TradeRecord = new TradeRecord(
            TradeRecord::INCREASE,
            '1', 'user_id',
            '123456', 'order_sn',
            '2', 'recommend_user_id',
            '500', '推荐用户下单奖励'
        );
        return json(self::createReturn($TradeRecord->createRrcord()));
    }

    /**
     * 减少用户余额
     * @return \think\response\Json
     */
    public function payTrade()
    {
        //模拟获取推荐用户下单奖励
        $TradeRecord = new TradeRecord(
            TradeRecord::PAY,
            '1', 'user_id',
            '789789', 'order_sn',
            '', '',
            '10', '购物商品订单'
        );
        return json(self::createReturn($TradeRecord->createRrcord()));
    }

    /**
     * 获取用户余额
     * @return \think\response\Json
     */
    public function tradeBalance()
    {
        $TradeRecord = new TradeRecord(
            '', '1', 'user_id'
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
        //模拟获取推荐用户下单奖励
        $IntegrationRecord = new IntegrationRecord(
            TradeRecord::INCREASE,
            '1', 'user_id',
            '123456', 'order_sn',
            '2', 'recommend_user_id',
            '500', '推荐用户下单奖励'
        );
        return json(self::createReturn($IntegrationRecord->createRrcord()));
    }

    /**
     * 减少用户积分
     * @return \think\response\Json
     */
    public function payIntegration()
    {
        //模拟获取推荐用户下单奖励
        $IntegrationRecord = new IntegrationRecord(
            TradeRecord::PAY,
            '1', 'user_id',
            '789789', 'order_sn',
            '', '',
            '10', '购物商品订单'
        );
        return json(self::createReturn($IntegrationRecord->createRrcord()));
    }

    /**
     * 获取用户积分
     * @return \think\response\Json
     */
    public function tradeIntegration()
    {
        $IntegrationRecord = new IntegrationRecord(
            '', '1', 'user_id'
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