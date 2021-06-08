<?php
/**
 * Author: cycle_3
 */

namespace app\member\model;

use think\Model;
use app\member\model\record\records\IntegrationRecord;
use app\member\model\record\records\TradeRecord;
use think\model\concern\SoftDelete;

/**
 * 用户等级管理
 * Class MemberGradeModel
 * @package app\member\model
 */
class MemberGradeModel extends Model
{

    use SoftDelete;

    protected $name = 'member_grade';
    protected $pk = 'member_grade_id';
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
     * @return array
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
        return createReturn(true,[
            'member_grade_id' => $details->member_grade_id
        ],'保存成功');
    }


    /**
     * 同步用户等级
     * @param $user_id
     * @return array
     */
    public function sysMemberGrade($user_id)
    {
        if(empty($user_id)) {
            return createReturn(false,[],'抱歉，该用户不存在');
        }

        $use_trade = (new TradeRecord('', $user_id, 'user_id'))->useTotal();
        $use_integration = (new IntegrationRecord('', $user_id, 'user_id'))->useTotal();


        $MemberConfigModel = new MemberConfigModel();
        $grade_trigger = $MemberConfigModel->getMembefConfig('grade_trigger')['data'];


        $where[] = ['is_display','=',1];
        if($grade_trigger == 1) {
            //消费积分达到设置积分即可
            $where[] = ['meet_integration','>=',$use_integration];
        } else if($grade_trigger == 2) {
            //消费金额达到设置金额即可
            $where[] = ['meet_trade','>=',$use_trade];
        } else {
            //积分和消费金额同时达到
            $where[] = ['meet_integration','>=',$use_integration];
            $where[] = ['meet_trade','>=',$use_trade];
        }

        //获取满足等级
        $member_grade_id = $this
            ->where($where)
            ->order('member_sort desc,member_grade_id desc')
            ->value('member_grade_id') ?: 0;

        $MemberModel = new MemberModel();
        $member = $MemberModel
            ->where('user_id','=',$user_id)
            ->findOrEmpty();
        if(!$member->isEmpty()) {
            $member->grade_id = $member_grade_id;
            $member->update_time = time();
            $member->save();
        }
        return createReturn(true,[],'同步成功');
    }

}