<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\member_record\records;

use app\member\model\member_record\MemberRecordIntegrationModel;


/**
 * 用户积分记录
 * Class IntegrationRecord
 * @package app\member\model\member_record\records
 */
class IntegrationRecord extends Record
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
        $MemberRecordIntegrationModel = new MemberRecordIntegrationModel();
        return $this->baseCreateRrcord($MemberRecordIntegrationModel);
    }

    /**
     * 获取用户余额
     * @return int
     */
    public function balance(){
        $MemberRecordIntegrationModel = new MemberRecordIntegrationModel();
        return $this->baseBalance($MemberRecordIntegrationModel);
    }

    /**
     * 获取记录列表
     * @return int
     */
    public function log($limit = 20){
        $MemberRecordIntegrationModel = new MemberRecordIntegrationModel();
        return $this->baseLog($MemberRecordIntegrationModel,$limit);
    }

    /**
     * 获取支出总量
     * @return int
     */
    public function useTotal(){
        $MemberRecordIntegrationModel = new MemberRecordIntegrationModel();
        return $this->baseUseTotal($MemberRecordIntegrationModel);
    }
}