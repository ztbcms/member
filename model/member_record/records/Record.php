<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\member_record\records;

use app\member\model\member_record\MemberRecordTradeModel;

class Record extends Base
{

    /**
     * 创建记录
     * @param $model
     * @return bool
     */
    public function baseCreateRrcord(
        $model
    )
    {
        $last_vaild_record = $model
            ->where('to', '=', $this->getTo())
            ->where('to_type', '=', $this->getToType())
            ->order('id desc')->value('id') ?: 0;

        $last_vaild_balance = $this->baseBalance($model); //获取最近的余额信息
        $balance = $last_vaild_balance + $this->getIncome() - $this->getPay();
        $model->findOrEmpty();
        $model->parent_id = $last_vaild_record;
        $model->to = $this->getTo();
        $model->to_type = $this->getToType();
        $model->from = $this->getFrom();
        $model->from_type = $this->getFromType();
        $model->target = $this->getTarget();
        $model->target_type = $this->getTargetType();
        $model->income = $this->getIncome();
        $model->pay = $this->getPay();
        $model->balance = $balance;
        $model->detail = $this->getDetail();
        $model->status = $this->getStatus();
        $model->create_time = time();
        $model->update_time = time();
        $model->remark = $this->getRemark();
        $model->to_name = $this->getToName();
        $model->from_name = $this->getFromName();
        $model->target_name = $this->getTargetName();
        return $model->save();
    }

    /**
     * 获取用户余额
     * @param $model
     * @return int
     */
    public function baseBalance($model)
    {
        $where[] = ['to', '=', $this->getTo()];
        $where[] = ['to_type', '=', $this->getToType()];
        $where[] = ['status', '=', self::STATUS_VAILD];
        $details = $model->where($where)->order('id desc')
            ->field('id,balance')
            ->findOrEmpty();
        if ($details->isEmpty()) {
            $balance = 0;
        } else {
            $balance = $details->balance;
        }
        return $balance;
    }

    /**
     * 获取操作记录
     * @param $model
     * @param $limit
     * @return mixed
     */
    public function baseLog($model,$limit){
        return $model
            ->where('to','=',$this->getTo())
            ->where('to_type','=',$this->getToType())
            ->order('id desc')
            ->paginate($limit);
    }

    /**
     * 获取支出的总量
     * @param $model
     * @return int
     */
    public function baseUseTotal($model){
        return $model
            ->where('to','=',$this->getTo())
            ->where('to_type','=',$this->getToType())
            ->where('pay','>',0)
            ->sum('pay') ?: 0;
    }

}