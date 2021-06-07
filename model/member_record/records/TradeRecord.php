<?php

namespace app\member\model\member_record\records;

use app\member\model\member_record\MemberRecordTradeModel;

/**
 * 用户余额记录
 * Class IntegralRecord
 * @package Record\member\model\member_record\records
 */
class TradeRecord extends Record
{

    /**
     * IntegralRecord constructor.
     *
     * @param $to
     * @param $to_name
     * @param $target
     * @param $target_type
     * @param $target_name
     */
    public function __construct(
        $types = self::INCREASE,
        $to = 0, $to_type = '',
        $from = '', $from_type = '',
        $target = '',$target_type = '',
        $amount = 0, $remark = ''
    )
    {
        $this->setTo($to);
        $this->setToType($to_type);
        $this->setFrom($from);
        $this->setFromType($from_type);
        $this->setTarget($target);
        $this->setTargetType($target_type);
        if($types == self::INCREASE) {
            $this->setIncome($amount);
        } else {
            $this->setPay($amount);
        }
        $this->setRemark($remark);
    }

    /**
     * 创建记录
     * @return bool
     */
    public function createRrcord(){
        $RecordTradeModel = new MemberRecordTradeModel();
        return $this->baseCreateRrcord($RecordTradeModel);
    }

    /**
     * 获取用户余额
     * @return int
     */
    public function balance(){
        $RecordTradeModel = new MemberRecordTradeModel();
        return $this->baseBalance($RecordTradeModel);
    }

    /**
     * 获取记录列表
     * @return int
     */
    public function log($limit = 20){
        $RecordTradeModel = new MemberRecordTradeModel();
        return $this->baseLog($RecordTradeModel,$limit);
    }

    /**
     * 获取支出总量
     * @return int
     */
    public function useTotal(){
        $RecordTradeModel = new MemberRecordTradeModel();
        return $this->baseUseTotal($RecordTradeModel);
    }
}