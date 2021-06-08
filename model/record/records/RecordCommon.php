<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\record\records;

use think\Facade\Db;

class RecordCommon extends RecordsBase
{

    /**
     * 创建记录
     * @param $model
     * @return bool
     */
    public function createRrcord()
    {

        $last_vaild_record =  Db::name($this->getTableName())
            ->where('to', '=', $this->getTo())
            ->where('to_type', '=', $this->getToType())
            ->order('id desc')->value('id') ?: 0;

        $last_vaild_balance = $this->balance(); //获取最近的余额信息
        $balance = $last_vaild_balance + $this->getIncome() - $this->getPay();

        $data['parent_id'] = $last_vaild_record;
        $data['to'] = $this->getTo();
        $data['to_type'] = $this->getToType();
        $data['from'] = $this->getFrom();
        $data['from_type'] = $this->getFromType();
        $data['target'] = $this->getTarget();
        $data['target_type'] = $this->getTargetType();
        $data['income'] = $this->getIncome();
        $data['pay'] = $this->getPay();
        $data['balance'] = $balance;
        $data['detail'] = $this->getDetail();
        $data['status'] = $this->getStatus();
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['remark'] = $this->getRemark();
        $data['to_name'] = $this->getToName();
        $data['from_name'] = $this->getFromName();
        $data['target_name'] = $this->getTargetName();
        return Db::name($this->getTableName())->insertGetId($data);
    }

    /**
     * 获取用户余额
     * @param $model
     * @return int
     */
    public function balance()
    {
        $where[] = ['to', '=', $this->getTo()];
        $where[] = ['to_type', '=', $this->getToType()];
        $where[] = ['status', '=', self::STATUS_VAILD];
        $details = Db::name($this->getTableName())->where($where)->order('id desc')
            ->field('id,balance')
            ->findOrEmpty();
        if (empty($details)) {
            $balance = 0;
        } else {
            $balance = $details['balance'];
        }
        return $balance;
    }

    /**
     * 获取操作记录
     * @param $model
     * @param $limit
     * @return mixed
     */
    public function log($limit){
        return Db::name($this->getTableName())
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
    public function useTotal(){
        return Db::name($this->getTableName())
            ->where('to','=',$this->getTo())
            ->where('to_type','=',$this->getToType())
            ->where('pay','>',0)
            ->sum('pay') ?: 0;
    }

}