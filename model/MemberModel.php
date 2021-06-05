<?php
/**
 * Author: jayinton
 */

namespace app\member\model;

use app\member\libs\Uiversal;
use think\Model;

class MemberModel extends Model
{
    protected $name = 'member';
    protected $pk = 'user_id';


    // 拉黑
    const IS_BLOCK_YES = 1;
    // 未拉黑
    const IS_BLOCK_NO = 0;

    // 待审核
    const AUDIT_STATUS_WIATING = 0;
    // 审核通过
    const AUDIT_STATUS_PASS = 1;
    // 审核不通过
    const AUDIT_STATUS_UNPASS = 2;

    /**
     * 对明文密码，进行加密，返回加密后的密码
     * @param string $identifier 为数字时，表示uid，其他为用户名
     * @param string $pass 明文密码，不能为空
     * @return string 返回加密后的密码
     */
    public function encryption($identifier, $pass, $verify = "")
    {
        $v = array();
        if (is_numeric($identifier)) {
            $v["id"] = $identifier;
        } else {
            $v["username"] = $identifier;
        }
        $pass = md5($pass . md5($verify));
        return $pass;
    }

    /**
     * 获取用户登录token
     * @param int $userid
     * @param string $salt
     * @return string
     */
    public function getToken($userid = 0,$salt = 'demo_cms_token')
    {
        $info = $this
            ->where(['user_id' => $userid])
            ->field('username,user_id,encrypt')
            ->findOrEmpty();
        if($info->isEmpty()) {
            return '';
        } else {
            //存在用户的情况下
            $guid = (new Uiversal())->getGuidV4();
            // 当前时间戳 (精确到毫秒)
            $timeStamp = microtime(true);
            // 自定义一个盐
            $token = md5("{$info['user_id']}_{$info['username']}_{$info['encrypt']}_{$timeStamp}_{$guid}_{$salt}");
            Cache($token, $info['user_id'], 86400 * 7);
            return $token;
        }
    }
}