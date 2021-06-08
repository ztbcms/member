<?php
/**
 * Author: jayinton
 */

namespace app\member\service;

use app\common\libs\helper\PasswordHelper;
use app\common\libs\helper\StringHelper;
use app\common\service\BaseService;
use app\member\model\MemberModel;
use app\member\validate\MemberValidate;
use think\facade\Request;
use app\member\model\MemberTokenModel;
use think\exception\ValidateException;

class MemberService extends BaseService
{
    // 添加、更新会员

    /**
     * 添加或者编辑会员信息
     * @param $post
     * @return array
     */
    static function addOrEditMember($post)
    {
        try {
            $post['source'] = $post['username'];
            $post['source_type'] = 'admin';

            if ($post['user_id']) $scene = 'edit_admin_user';
            else $scene = 'add_admin_user';

            validate(MemberValidate::class)
                ->scene($scene)
                ->check($post);

            $MemberModel = new MemberModel();
            $isCount = $MemberModel
                ->where('username', $post['username'])
                ->where('user_id', '<>', $post['user_id'])
                ->count();
            if (!empty($isCount)) {
                return self::createReturn(false, '', '抱歉，该用户名已经存在');
            }

            $member = $MemberModel
                ->where('user_id', '=', $post['user_id'])
                ->findOrEmpty();
            if ($member->isEmpty()) {
                $member->reg_time = time();
                $member->reg_ip = Request::ip();
            }

            if (isset($post['password']) && !empty($post['password'])) {
                $encrypt = StringHelper::genRandomString(6);
                $member->encrypt = $encrypt;
                $member->password = PasswordHelper::hashPassword($post['password'], $encrypt);
            }

            if (isset($post['username'])) $member->username = $post['username'];
            if (isset($post['email'])) $member->email = $post['email'];
            if (isset($post['phone'])) $member->phone = $post['phone'];
            if (isset($post['nickname'])) $member->nickname = $post['nickname'];
            if (isset($post['remark'])) $member->remark = $post['remark'];
            if (isset($post['role_id'])) $member->role_id = $post['role_id'];
            if (isset($post['source'])) $member->source = $post['source'];
            if (isset($post['source_type'])) $member->source_type = $post['source_type'];
            if (isset($post['audit_status'])) $member->audit_status = MemberModel::AUDIT_STATUS_PASS;
            if (isset($post['is_block'])) $member->is_block = MemberModel::IS_BLOCK_NO;
            $member->update_time = time();
            $member->save();

            return self::createReturn(true, [
                'user_id' => $member->user_id
            ], '操作成功');
        } catch (ValidateException $e) {
            return createReturn(false, '', $e->getError());
        }
    }


    /**
     * 用户审核
     * @param $user_id
     * @param $audit_status
     * @return array
     */
    static function auditMember($user_id = 0, $audit_status = 0)
    {
        $MemberModel = new MemberModel();
        $member = $MemberModel
            ->where('user_id','=',$user_id)
            ->findOrEmpty();
        $member->audit_status = $audit_status;
        $member->update_time = time();
        $member->save();
        return createReturn(true, null, '操作成功');
    }

    /**
     * 用户拉黑
     * @param $user_id
     * @param $is_block
     * @return array
     */
    static function blockMember($user_id = 0, $is_block = 0)
    {
        $MemberModel = new MemberModel();
        $member = $MemberModel
            ->where('user_id','=',$user_id)
            ->findOrEmpty();
        $member->is_block = $is_block;
        $member->update_time = time();
        $member->save();
        return createReturn(true, null, '操作成功');
    }

    /**
     * 用户登录或者注册
     * @param string $username
     * @param string $password
     * @param string $source
     * @param string $source_type
     * @return array
     */
    static function memberLoginRegister(
        $username = '', $password = '', $source = '', $source_type = ''
    )
    {
        $MemberModel = new MemberModel();
        $isCount = $MemberModel
            ->where('username', '=', $username)
            ->count();
        if (empty($isCount)) {
            return self::membeRegister($username, $password, $source, $source_type);
        } else {
            return self::memberLogin($username, $password);
        }
    }

    /**
     * 用户注册
     * @param string $username
     * @param string $password
     * @param string $source
     * @param string $source_type
     * @return array
     */
    static function membeRegister($username = '', $password = '', $source = '', $source_type = '')
    {
        try {
            validate(MemberValidate::class)
                ->scene('register')
                ->check([
                    'username' => $username,
                    'password' => $password,
                    'source' => $source,
                    'source_type' => $source_type
                ]);

            $MemberModel = new MemberModel();
            $member = $MemberModel
                ->where('username', '=', $username)
                ->findOrEmpty();
            if (!$member->isEmpty()) {
                return createReturn(false, '', '抱歉，账号已存在');
            }

            //随机码
            $encrypt = $MemberModel->genRandomString(6);
            $member->username = $username;
            $member->password = $MemberModel->encryption($password, $encrypt);
            $member->encrypt = $encrypt;
            $member->audit_status = 0;
            $member->sex = 0;
            $member->reg_time = time();
            $member->reg_ip = $_SERVER["REMOTE_ADDR"];
            $member->is_block = 0;
            $member->role_id = 0;
            $member->source = $source;
            $member->source_type = $source_type;
            $member->update_time = time();
            $member->save();

            return createReturn(true,
                [
                    'user_id' => $member->user_id,
                    'token' => (new MemberTokenModel())->getToken($member->user_id)
                ]
            );

        } catch (ValidateException $e) {
            return createReturn(false, '', $e->getError());
        }
    }

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @param bool $ignore_password
     * @return array
     */
    static function memberLogin($username, $password, $ignore_password = false)
    {
        try {
            validate(MemberValidate::class)
                ->scene('login')
                ->check([
                    'username' => $username,
                    'password' => $password,
                ]);

            $MemberModel = new MemberModel();
            $member = $MemberModel->where('username', '=', $username)->findOrEmpty();
            if ($member->isEmpty()) {
                return self::createReturn(false, [], '抱歉，该账号不存在');
            }

            if (!$ignore_password) {
                $password = $MemberModel->encryption($password, $member->encrypt);
                if ($password != $member->password) {
                    return self::createReturn(false, [], '密码错误');
                }
            }

            return createReturn(true,
                [
                    'user_id' => $member->user_id,
                    'token' => (new MemberTokenModel())->getToken($member->user_id)
                ]
            );
        } catch (ValidateException $e) {
            return createReturn(false, '', $e->getError());
        }
    }
}