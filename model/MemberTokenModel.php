<?php
/**
 * Author: cycle_3
 */

namespace app\member\model;

use app\common\util\Encrypt;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * 创建用户token
 * Class MemberTokenModel
 * @package app\member\model
 */
class MemberTokenModel extends Model
{

    use SoftDelete;

    protected $name = 'member_token';
    protected $pk = 'member_token_id';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 获取token
     * @param  int  $user_id
     * @return string
     */
    public function getToken($user_id = 0)
    {
        $MemberModel = new MemberModel();
        $member = $MemberModel
            ->where('user_id', '=', $user_id)
            ->findOrEmpty();
        if ($member->isEmpty()) {
            return '';
        }

        $config = \think\facade\Config::get('passport');
        if (isset($config['token_expire'])) {
            $token_expire = $config['token_expire'];
        } else {
            $token_expire = 7 * 86400;
        }

        //获取token
        $key = $member['user_id'].'_'.$member['username'].'_'.$member['encrypt'];
        $access_token = Encrypt::authcode((int) $key, '').Encrypt::authcode((int) $key, '');

        $this->user_id = $member->user_id;
        $this->access_token = $access_token;
        $this->expires_in = time() + $token_expire;
        $this->create_time = time();
        $this->update_time = time();
        $this->save();
        return $access_token;
    }

    /**
     * 获取tokent用户
     * @param  string  $access_token
     * @return int
     */
    public function decodeToken($access_token = '')
    {
        return $this
            ->where('access_token', '=', $access_token)
            ->where('expires_in', '>', time())
            ->value('user_id') ?: 0;
    }

}