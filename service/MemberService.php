<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 18:23
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberModel;
use think\facade\Db;

class MemberService extends BaseService
{
    // 登录、获取用户信息
    // 获取上传头像 更新用户基本资料 检查用户名  检查 Email 地址 注册会员

    /**
     * 注册会员
     * @param string $username 用户名
     * @param string $password 明文密码
     * @param string $email 邮箱
     * @return boolean
     */
    public function userRegister($username, $password, $email) {
        //检查用户名
        $ckname = $this->userCheckUsername($username);
        if ($ckname !== true) {
            return false;
        }
        //检查邮箱
        $ckemail = $this->userCheckeMail($email);
        if ($ckemail !== true) {
            return false;
        }
        $Member = new MemberModel();
        $encrypt = genRandomString(6);
        $password = $Member->encryption(0, $password, $encrypt);
        $data = array(
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "encrypt" => $encrypt,
            "amount" => 0,
        );
        $userId = $Member->create($data);
        if ($userId) {
            // TODO HOOK
//            Hook::listen('member_register', MemberRegisterBehaviorParam::create(['userid' => $userid]));
            return $userId;
        }
        $this->error = $Member->error ?: '注册失败！';
        return false;
    }

    /**
     * 检查用户名
     * @param string $username 用户名
     * @return boolean|int
     */
    public function userCheckUsername($username) {
        if(empty($username)){
            $this->error = '用户名不能为空！';
            return false;
        }
        $guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
        if (!preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $username)) {
            $find = MemberModel::where("username", $username)->count();
            if ($find) {
                $this->error = '该用户名已经存在！';
                return false;
            }
            return true;
        }
        $this->error = '用户名不合法！';
        return false;
    }

    /**
     * 检查 Email 地址
     * @param string $email 邮箱地址
     * @return boolean
     */
    public function userCheckeMail($email) {
        if (strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) {
            $find = MemberModel::where("username", $email)->count();
            if ($find) {
                $this->error = '该 Email 已经被注册！';
                return false;
            }
            return true;
        }
        $this->error = 'Email 格式有误！';
        return false;
    }

    /**
     * 获取用户信息
     * @param string $identifier 用户/UID
     * @param string $password 明文密码，填写表示验证密码
     * @return array|boolean
     */
    public function getLocalUser($identifier, $password = null) {
        $map = array();
        if (empty($identifier)) {
            $this->error = '参数为空！';
            return false;
        }
        if (is_int($identifier)) {
            $map['userid'] = $identifier;
        } else {
            $map['username'] = $identifier;
        }
        $UserModel = new MemberModel();
        $user = $UserModel->where($map)->find();
        if (empty($user)) {
            $this->error = '该用户不存在！';
            return false;
        }
        //是否需要进行密码验证
        if (!empty($password)) {
            $encrypt = $user["encrypt"];
            //对明文密码进行加密
            $password = $UserModel->encryption($identifier, $password, $encrypt);
            if ($password != $user['password']) {
                $this->error = '用户密码错误！';
                //密码错误
                return false;
            }
        }
        //用户附表信息
        $user_model = Db::name('model')->where('modelid' , $user['modelid'] )->find();
        $user_data = [];
        if($user_model){
            $user_data = Db::table($user_model['tablename'])->where('userid' , $user['userid'])->find();
        }
        $user['data'] = $user_data;

        return $user;
    }
}
