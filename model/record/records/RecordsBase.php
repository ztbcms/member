<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\record\records;

/**
 * 公共使用的方法
 * Class Base
 * @package app\member\model\member_record\records
 */
abstract class RecordsBase
{

    //添加
    const INCREASE = 'increase';
    //减少
    const PAY = 'pay';

    /**
     * 正常状态
     */
    const STATUS_VAILD = 0;
    /**
     * 非法状态
     */
    const STATIS_INVAILD = 1;
    /**
     * 冻结状态
     */
    const STATUS_FROZEN = 2;

    public $table_name = '';

    protected $id = '';
    protected $parent_id = '';
    protected $to = '';
    protected $to_type = '';
    protected $from = '';
    protected $from_type = '';
    protected $target = '';
    protected $target_type = '';
    protected $income = 0;
    protected $pay = 0;
    protected $balance = 0;
    protected $detail = '';
    protected $status = 0;
    protected $create_time = '';
    protected $update_time = '';
    protected $remark = '';
    protected $to_name = '';
    protected $from_name = '';
    protected $target_name = '';
    protected $delete_time = '';

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * @param string $table_name
     */
    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param string $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getToType()
    {
        return $this->to_type;
    }

    /**
     * @param string $to_type
     */
    public function setToType($to_type)
    {
        $this->to_type = $to_type;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getFromType()
    {
        return $this->from_type;
    }

    /**
     * @param string $from_type
     */
    public function setFromType($from_type)
    {
        $this->from_type = $from_type;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTargetType()
    {
        return $this->target_type;
    }

    /**
     * @param string $target_type
     */
    public function setTargetType($target_type)
    {
        $this->target_type = $target_type;
    }

    /**
     * @return string
     */
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * @param string $income
     */
    public function setIncome($income)
    {
        $this->income = $income;
    }

    /**
     * @return string
     */
    public function getPay()
    {
        return $this->pay;
    }

    /**
     * @param string $pay
     */
    public function setPay($pay)
    {
        $this->pay = $pay;
    }

    /**
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @param string $create_time
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;
    }

    /**
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * @param string $update_time
     */
    public function setUpdateTime($update_time)
    {
        $this->update_time = $update_time;
    }

    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return string
     */
    public function getToName()
    {
        return $this->to_name;
    }

    /**
     * @param string $to_name
     */
    public function setToName($to_name)
    {
        $this->to_name = $to_name;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @param string $from_name
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
    }

    /**
     * @return string
     */
    public function getTargetName()
    {
        return $this->target_name;
    }

    /**
     * @param string $target_name
     */
    public function setTargetName($target_name)
    {
        $this->target_name = $target_name;
    }

    /**
     * @return string
     */
    public function getDeleteTime()
    {
        return $this->delete_time;
    }

    /**
     * @param string $delete_time
     */
    public function setDeleteTime($delete_time)
    {
        $this->delete_time = $delete_time;
    }

}