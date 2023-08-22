<?php
/**
 * Author: jayinton
 */

namespace app\member\model;

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
     * 获取身份名称
     * @return \think\model\relation\HasOne
     */
    public function roleInfo()
    {
        return $this->hasOne(MemberRoleModel::class, 'id', 'role_id')
            ->field('id,name as role_name');
    }

    /**
     * 获取等级名称
     * @return \think\model\relation\HasOne
     */
    public function gradeInfo()
    {
        return $this->hasOne(MemberGradeModel::class, 'grade_id', 'member_grade_id')
            ->field('member_grade_id,member_grade_name as grade_name');
    }

    /**
     * 对明文密码，进行加密，返回加密后的密码
     * @param  string  $pass  明文密码，不能为空
     * @param  string  $verify
     * @return string 返回加密后的密码
     */
    public function encryption($pass = '', $verify = "")
    {
        $pass = md5($pass.md5($verify));
        return $pass;
    }

    /**
     * 产生一个指定长度的随机字符串,并返回给用户
     * @param  int  $len  产生字符串的长度
     * @return string 随机字符串
     */
    public function genRandomString($len = 6)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9",
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}