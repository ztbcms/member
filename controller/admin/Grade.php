<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberGradeModel;
use app\member\validate\MemberGradeValidate;
use think\exception\ValidateException;

/**
 * 用户等级管理
 * Class Grade
 * @package app\member\controller\admin
 */
class Grade extends AdminController
{

    /**
     * 用户等级列表
     * @return \think\response\Json|\think\response\View
     */
    public function index()
    {
        $_action = input('_action','','trim');
        if($_action == 'list') {
            //列表
            $where = [];
            $member_grade_name = input('member_grade_name','','trim');
            if($member_grade_name) $where[] = ['member_grade_name','like','%'.$member_grade_name.'%'];

            $MemberGradeModel = new MemberGradeModel();
            $list = $MemberGradeModel
                ->where($where)
                ->order('member_sort desc,member_grade_id desc')
                ->paginate(input('limit'));
            return json(self::createReturn(true,$list));
        } else if($_action == 'delete') {
            //删除
            $member_grade_id = input('member_grade_id','','trim');
            $MemberGradeModel = new MemberGradeModel();
            $MemberGradeModel
                ->where('member_grade_id','=',$member_grade_id)
                ->findOrEmpty()->delete();
            return json(self::createReturn(true,'操作成功'));
        }
        return view();
    }

    /**
     * 详情
     * @return \think\response\Json|\think\response\View
     */
    public function details()
    {
        $_action = input('_action','','trim');
        if($_action == 'submit') {
            //提交内容
            try {
                $post = input('post.');
                validate(MemberGradeValidate::class)
                    ->scene('submit')
                    ->check($post);

                $MemberGradeModel = new MemberGradeModel();
                return json($MemberGradeModel->submit($post));
            } catch (ValidateException $e) {
                return json(createReturn(false, '', $e->getError()));
            }
        } else if($_action == 'details') {
            //获取等级详情
            $member_grade_id = input('member_grade_id','','trim');
            $MemberGradeModel = new MemberGradeModel();
            $details = $MemberGradeModel
                ->where('member_grade_id','=',$member_grade_id)
                ->findOrEmpty();
            return json(createReturn(true, $details, '操作成功'));
        }
        return view('addOrEditGrade');
    }

}