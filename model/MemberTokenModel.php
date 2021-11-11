<?php
/**
 * Author: cycle_3
 */

namespace app\member\model;

use think\Exception;
use think\facade\Config;
use think\Model;

/**
 * 创建用户token
 * Class MemberTokenModel
 *
 * @package app\member\model
 */
class MemberTokenModel extends Model
{
    protected $name = 'member_token';

    /**
     * 生成用户token
     *
     * @param  int  $user_id
     *
     * @return string
     */
    static function generateToken($user_id, $token_type = 'auth')
    {
        $config = Config::get('passport');
        if (isset($config['token_expire'])) {
            $token_expire = time() + $config['token_expire'];
        } else {
            $token_expire = time() + 7 * 86400;
        }
        //获取token
        $key = $user_id.microtime(true);
        $access_token = hash('sha256', $key);
        $result = MemberTokenModel::insert([
            'user_id'      => $user_id,
            'access_token' => $access_token,
            'expires_in'   => $token_expire,
            'token_type'   => $token_type,
        ]);
        throw_if(!$result, new Exception('生成access_token异常'));
        return $access_token;
    }

    /**
     * 获取token用户
     *
     * @param  string  $access_token
     *
     * @return int
     */
    static function getUserIdByToken($access_token = '')
    {
        $token = MemberTokenModel::where('access_token', '=', $access_token)->find();
        if ($token && $token['expires_in'] >= time()) {
            return $token['user_id'];
        }
        return null;
    }

}