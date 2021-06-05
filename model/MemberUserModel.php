<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:14
 */

namespace app\member\model;

use app\cms\model\ModelModel;
use app\member\libs\Uiversal;
use think\facade\Db;
use think\Model;

/**
 * 会员
 * Class MemberUserModel
 * @package app\member\model
 */
class MemberUserModel extends Model
{
    protected $name = 'member';
    protected $pk = 'user_id';

    // 已审核
    const IS_CHECKED = 1;
    // 拉黑
    const IS_BLOCK = 1;
    // 取消审核
    const NO_CHECKED = 0;
    // 取消拉黑
    const NO_BLOCK = 0;


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
