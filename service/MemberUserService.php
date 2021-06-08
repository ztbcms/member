<?php

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberModel;
use app\member\model\MemberTokenModel;
use app\member\validate\MemberValidate;
use think\exception\ValidateException;

/**
 * 会员
 * Class MemberUserService
 * @package app\member\service
 */
class MemberUserService extends BaseService
{
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
