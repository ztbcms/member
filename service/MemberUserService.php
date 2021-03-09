<?php

namespace app\member\service;

use app\common\model\UserModel;
use app\common\service\BaseService;
use app\member\libs\util\Encrypt;
use app\member\model\MemberBindModel;
use app\member\model\MemberConnectTokenModel;
use app\member\model\MemberModel;
use app\member\model\MemberOpenModel;
use app\member\model\MemberUserModel;
use think\facade\Db;

/**
 * 会员
 * Class MemberUserService
 * @package app\member\service
 */
class MemberUserService extends BaseService
{

    /**
     * 获取用户列表
     * @param array $where
     * @param int $limit
     */
    static function getList($where = [],$page = 1, $limit = 15)
    {
        $data = MemberUserModel::where($where)
            ->order('create_time', 'desc')
            ->page($page)->limit($limit)->select();
        $total = MemberUserModel::where($where)->count();
        return self::createReturnList(false, $data, $page, $limit, $total, ceil($total / $limit));
    }

    /**
     * 获取详情
     * @param $userId
     * @return array|\think\Model
     */
    static function getDetail($userId)
    {
        $user = MemberUserModel::where('user_id', $userId)->findOrEmpty();
        if (!$user->isEmpty()) {
            // 查询标签
            $user['tag_ids'] = MemberTagBindService::getUserTagIds($userId);
            // 用户关联模型信息
            if (empty($user['info'])) $user['info'] = [];
        }
        return $user;
    }

    /**
     * 注册会员
     * @param string $username 用户名
     * @param string $password 明文密码
     * @param string $email 邮箱
     */
    function userRegister($username, $password, $email = '')
    {
        // 检查用户名
        $res = $this->checkUsername($username);
        if (!$res['status']) {
            return $res;
        }
        // 检查邮箱
        if (!empty($email)) {
            $res = $this->checkEmail($email);
            if (!$res['status']) {
                return $res;
            }
        }
        // 检查密码
        $res = $this->checkPassword($password);
        if (!$res['status']) {
            return $res;
        }

        $Member = new MemberUserModel();
        // 密码加密
        $encrypt = genRandomString(6);
        $password = $this->encryption(0, $password, $encrypt);
        $data = [
            "username"    => $username,
            "password"    => $password,
            "email"       => $email,
            "encrypt"     => $encrypt,
            "create_time" => time(),
            "reg_date"    => time(),
            "reg_ip"      => request()->ip(),
        ];
        $userId = $Member->insertGetId($data);
        if ($userId) {
            return self::createReturn(true, ['user_id' => $userId], '注册成功');
        }
        return self::createReturn(false, null, '注册失败');
    }

    function userLogin($username, $password, $ignore_password = false){
        $memberModel = new MemberUserModel();
        $member = $memberModel->where('username', $username)->find();
        if(!$member){
            return self::createReturn(false, null, '用户未注册');
        }
        $member = $member->toArray();
        if(!$ignore_password){
            $password = $this->encryption(0, $password, $member['encrypt']);
            if($password != $member['password']){
                return self::createReturn(false, null, '密码错误');
            }
        }
        unset($member['password']);
        unset($member['encrypt']);
        return self::createReturn(true, $member, '登录成功');
    }

    /**
     * 注册用户登录状态
     * 获取token
     * @param int $userId
     * @param int $openId
     * @param int $openAppId
     * @param string $appTypeName
     * @return bool|string
     */
    static function loginToken(int $userId, $openId = 0, $openAppId = 0, $appTypeName = 'Local')
    {
        $token = \app\common\util\Encrypt::authcode($userId, '');

        $data = [
            'uid'           => $userId,
            'open_id'       => $openId,
            'open_app_id'   => $openAppId,
            'access_token'  => $token,
            'app_type_name' => $appTypeName,
            'expires_in'    => time() + 7 * 86400,
            'create_time'   => time()
        ];
        $res = MemberConnectTokenModel::create($data);
        if($res) {
            return $token;
        }
        return false;
    }

    /**
     * 编辑用户
     * @param string $username 用户名
     * @param string $oldpw 旧密码
     * @param string $newpw 新密码，如不修改为空
     * @param string $email Email，如不修改为空
     * @param int $ignoreoldpw 是否忽略旧密码
     * @param array $data 其他信息
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function userEdit($username, $oldpw, $newpw = '', $email = '', $ignoreoldpw = 0, $data = array())
    {
        $memberModel = new MemberUserModel();
        //验证旧密码是否正确
        if ($ignoreoldpw == 0) {
            $info = $memberModel->where("username", $username)->find();
            $pas = $this->encryption(0, $oldpw, $info['encrypt']);
            if ($pas != $info['password']) {
                $this->error = '旧密码错误！';
                return false;
            }
        }
        if ($newpw) {
            //随机密码
            $encrypt = genRandomString(6);
            //新密码
            $password = $this->encryption(0, $newpw, $encrypt);
            $data['password'] = $password;
            $data['encrypt'] = $encrypt;
        } else {
            unset($data['password'], $data['encrypt']);
        }
        if ($email) {
            $data['email'] = $email;
        } else {
            unset($data['email']);
        }
        if (empty($data)) {
            return true;
        }
        if ($memberModel->where("username", $username)->save($data) !== false) {
            return true;
        } else {
            $this->error = '用户资料更新失败！';
            return false;
        }
    }


    /**
     * 检查用户名
     *
     * @param  string  $username  用户名
     *
     * @return array
     */
    function checkUsername($username)
    {
        if (empty($username)) {
            return self::createReturn(false, null, '用户名不能为空');
        }
        $find = MemberUserModel::where("username", $username)->find();
        if ($find) {
            return self::createReturn(false, null, '用户名已存在');
        }
        return self::createReturn(true, null, '验证通过');
    }

    /**
     * 检查密码
     *
     * @param $password
     *
     * @return array
     */
    public function checkPassword($password)
    {
        if (empty($password)) {
            return self::createReturn(false, null, '密码不能为空');
        }
        return self::createReturn(true, null, '验证通过');
    }

    /**
     * 获取用户信息
     * @param $identifier 用户/UID
     * @param null $password 明文密码，填写表示验证密码
     * @return array|bool|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLocalUser($identifier, $password = null)
    {
        $map = array();
        if (empty($identifier)) {
            $this->error = '参数为空！';
            return false;
        }
        if (is_int($identifier)) {
            $map['user_id'] = $identifier;
        } else {
            $map['username'] = $identifier;
        }
        $UserModel = new MemberUserModel();
        $user = $UserModel->where($map)->findOrEmpty();
        if ($user->isEmpty()) {
            $this->error = '该用户不存在！';
            return false;
        }
        //是否需要进行密码验证
        if (!empty($password)) {
            $encrypt = $user["encrypt"];
            //对明文密码进行加密
            $password = $this->encryption($identifier, $password, $encrypt);
            if ($password != $user['password']) {
                $this->error = '用户密码错误！';
                //密码错误
                return false;
            }
        }
        //用户附表信息
        $user_model = Db::name('model')->where('modelid', $user['modelid'])->find();
        $user_data = [];
        if ($user_model) {
            $user_data = Db::name($user_model['tablename'])->where('userid', $user['user_id'])->find();
        }
        $user['data'] = $user_data;

        return $user;
    }

    /**
     * 检查 Email 地址
     *
     * @param  string  $email  邮箱地址
     *
     * @return array
     */
    function checkEmail($email)
    {
        if (strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) {
            return self::createReturn(true, null, '');
        }
        return self::createReturn(false, null, '邮箱格式有误');
    }


    /**
     * 对明文密码，进行加密，返回加密后的密码
     * @param string $identifier 为数字时，表示uid，其他为用户名
     * @param string $pass 明文密码，不能为空
     * @param string $verify
     * @return string 返回加密后的密码
     */
    protected function encryption($identifier, $pass, $verify = "")
    {
        $v = array();
        if (is_numeric($verify)) {
            $v["id"] = $identifier;
        } else {
            $v["username"] = $identifier;
        }
        $pass = md5($pass . md5($verify));
        return $pass;
    }

    /**
     * 删除头像
     * @param $userId
     * @return bool
     */
    public static function delAvatar($userId)
    {
        return MemberUserModel::where('user_id', $userId)->save(['userpic' => null]);
    }

    /**
     * 拉黑，启用用户批量
     * @param $userIds array
     * @param $isBlock
     */
    static function blockUser($userIds, $isBlock)
    {
        $count = 0;
        foreach ($userIds as $userId) {
            $count += MemberModel::where('user_id', $userId)->save(['is_block' => $isBlock]);
        }
        if ($count > 0) {
            return self::createReturn(true, null, '操作成功');
        }
        return self::createReturn(false, null, '操作失败');
    }

    /**
     * 审核用户状态
     * @param $userIds
     * @param $status
     * @return bool
     */
    public static function auditUser($userIds, $status)
    {
        $count = 0;
        foreach ($userIds as $userId) {
            $count += MemberUserModel::where('user_id', $userId)->save(['checked' => $status]);
        }
        if ($count > 0) {
            return true;
        }
        return false;
    }

    /**
     * 删除用户批量
     * @param $userIds
     * @return bool
     */
    public static function batchDelUser($userIds)
    {
        return MemberUserModel::whereIn('user_id', $userIds)->useSoftDelete('delete_time', time())->delete();
    }

    /**
     * 更新登录状态信息
     * @param string $userId
     * @return boolean|array
     */
    public static function loginStatus($userId)
    {
        $data['last_login_time'] = time();
        $data['last_login_ip'] = request()->ip();
        return UserModel::where('id', $userId)->save($data);
    }

    /**
     * 登录或者注册用户
     * @param $username
     * @param $password
     * @return array
     */
    function getLoginRegisterUser($username, $password){
        $MemberUserModel = new MemberUserModel();
        $memberFind = $MemberUserModel->where(['username'=>$username])->find();
        if($memberFind) {
            $userId = $memberFind['user_id'];
        } else {
            $res = $this->userRegister($username, $password);
            if(!$res['status']) {
                return $res;
            }
            $userId = $res['data']['user_id'];
        }
        $token = Encrypt::authcode((int) $userId, Encrypt::OPERATION_ENCODE,'ZTBCMS',86400);
        return self::createReturn(true,
            [
                'user_id' => $userId,
                'token' => $token
            ]
        );
    }

    /**
     * 更新用户数据
     * @param string $user_id
     * @param array $data
     * @return array
     */
    public function sysUserInfo($user_id = '',$data = []){
        $MemberUserModel = new MemberUserModel();
        $save = [];
        if(isset($data['sex'])) $save['sex'] = $data['sex'];  //性别
        if(isset($data['nickname'])) $save['nickname'] = $data['nickname']; //用户昵称
        if(isset($data['userpic'])) $save['userpic'] = $data['userpic']; //头像
        if(isset($data['phone'])) $save['phone'] = $data['phone']; //手机号
        if(!empty($save)) $MemberUserModel->where(['user_id'=>$user_id])->update($save);
        return self::createReturn(true);
    }

}
