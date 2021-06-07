<?php
/**
 * Author: cycle_3
 */

namespace app\member\model;

use app\member\model\member_record\records\IntegrationRecord;
use app\member\model\member_record\records\TradeRecord;
use think\model\concern\SoftDelete;

class MemberGradeModel extends MemberBaseModel
{

    protected $name = 'member_grade';
    protected $pk = 'member_grade_id';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 数据类型转换
     * @param $value
     * @return string
     */
    public function getIsDisplayAttr($value)
    {
        return (string)$value;
    }

    /**
     * 提交内容
     * @param $post
     * @return bool
     */
    public function submit($post)
    {
        $details = $this
            ->where('member_grade_id', '=', $post['member_grade_id'])
            ->findOrEmpty();
        if (isset($post['member_grade_name'])) $details->member_grade_name = $post['member_grade_name'];
        if (isset($post['meet_integration'])) $details->meet_integration = $post['meet_integration'];
        if (isset($post['meet_trade'])) $details->meet_trade = $post['meet_trade'];
        if (isset($post['member_sort'])) $details->member_sort = $post['member_sort'];
        if (isset($post['discount'])) $details->discount = $post['discount'];
        if (isset($post['is_display'])) $details->is_display = $post['is_display'];
        $details->create_time = time();
        $details->update_time = time();
        $details->save();
        return $details->member_grade_id;
    }

    /**
     * 同步用户等级
     * @param $user_id
     * @return bool
     */
    public function sysMemberGrade($user_id)
    {
        if(empty($user_id)) {
            $this->setZtbMessage('抱歉，用户该用户不存在');
            return false;
        }

        $use_trade = (new TradeRecord('', $user_id, 'user_id'))->useTotal();
        $use_integration = (new IntegrationRecord('', $user_id, 'user_id'))->useTotal();

        //获取满足等级
        $member_grade_id = $this->where('meet_integration', '>=', $use_integration)
            ->where('meet_trade', '>=', $use_trade)
            ->where('is_display','=',1)
            ->order('member_sort desc,member_grade_id desc')
            ->value('member_grade_id') ?: 0;

        $MemberModel = new MemberModel();
        $member = $MemberModel
            ->where('user_id','=',$user_id)
            ->findOrEmpty();
        $member->grade_id = $member_grade_id;
        $member->update_time = time();
        $member->save();
        return true;
    }

}