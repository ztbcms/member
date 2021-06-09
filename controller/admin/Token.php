<?php
/**
 * Author: cycle_3
 */

namespace app\member\controller\admin;

use app\common\controller\AdminController;
use app\member\model\MemberModel;
use app\member\model\MemberTokenModel;

/**
 * token管理
 * Class Token
 * @package app\member\controller\admin
 */
class Token extends AdminController
{

    /**
     * 凭证管理
     * @return \think\response\Json|\think\response\View
     */
    function index()
    {
        $action = input('_action', '', 'trim');
        if ($action == 'list') {
            //列表
            $where = [];
            $user_id = input('user_id', '', 'trim');
            if ($user_id) {
                $where[] = ['user_id', 'like', '%'.$user_id.'%'];
            }

            $access_token = input('access_token', '', 'trim');
            if ($access_token) {
                $where[] = ['access_token', 'like', '%'.$access_token.'%'];
            }

            $MemberTokenModel = new MemberTokenModel();
            $list = $MemberTokenModel
                ->where($where)
                ->order('create_time desc')
                ->paginate();
            return json(self::createReturn(true, $list));
        } else {
            if ($action == 'delete') {
                //删除
                $MemberTokenModel = new MemberTokenModel();
                $MemberTokenModel
                    ->where('access_token_id', '=', input('access_token_id'))
                    ->findOrEmpty()->delete();
                return json(self::createReturn(true, '', '删除成功'));
            }
        }
        return view();
    }

    /**
     * 凭证详情
     * @return \think\response\Json|\think\response\View
     */
    public function details()
    {
        $action = input('_action', '', 'trim');
        if ($action == 'submit') {
            return json(self::createReturn(true, [
                'token' => (new MemberTokenModel())->getToken(input('user_id'))
            ], '生成成功'));
        } else {
            if ($action == 'member') {
                $MemberModel = new MemberModel();
                $list = $MemberModel->select();
                return json(self::createReturn(true, $list));
            }
        }
        return view('addOrEditToken');
    }

}