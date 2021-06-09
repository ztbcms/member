<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\record\records;

/**
 * 用户积分记录
 * Class IntegrationRecord
 * @package app\member\model\member_record\records
 */
class IntegrationRecord extends RecordCommon
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
        $to = 0,
        $to_type = '',
        $from = '',
        $from_type = '',
        $target = '',
        $target_type = '',
        $amount = 0,
        $remark = ''
    ) {
        $this->setTableName('member_record_integration');
        $this->setTo($to);
        $this->setToType($to_type);
        $this->setFrom($from);
        $this->setFromType($from_type);
        $this->setTarget($target);
        $this->setTargetType($target_type);
        if ($types == self::INCREASE) {
            $this->setIncome($amount);
        } else {
            $this->setPay($amount);
        }
        $this->setRemark($remark);
    }
}