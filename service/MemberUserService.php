<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 15:29
 */

namespace app\member\service;

use app\common\model\UserModel;
use app\common\model\UserTokenModel;
use app\common\service\BaseService;
use app\member\libs\util\Encrypt;
use app\member\model\MemberUserModel;
use think\facade\Db;

/**
 * 会员
 * Class MemberUserService
 * @package app\member\service
 */
class MemberUserService extends BaseService
{
    //存储用户uid的Key
    const userUidKey = 'spf_userid';

    /**
     * 获取用户列表
     * @param array $where
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    static function getList($where = [], $limit = 15)
    {
        return MemberUserModel::where($where)
            ->order('create_time', 'desc')
            ->paginate($limit);
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
     * @return boolean
     */
    public function userRegister($username, $password, $email)
    {
        // 检查用户名
        $ckName = $this->CheckUsername($username);
        if ($ckName !== true) {
            return false;
        }
        // 检查邮箱
        $ckEmail = $this->CheckEmail($email);
        if ($ckEmail !== true) {
            return false;
        }
        // 检查密码
        $checkPassword = $this->checkPassword($password);
        if ($checkPassword !== true) {
            return false;
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
            "amount"      => 0,
            "create_time" => time(),
            "reg_date"    => time(),
            "reg_ip"      => request()->ip(),
        ];
        $userId = $Member->insertGetId($data);
        if ($userId) {
            // TODO HOOK
//            Hook::listen('member_register', MemberRegisterBehaviorParam::create(['userid' => $userid]));
            return $userId;
        }
        $this->error = $Member->error ?: '注册失败！';
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
     * @param string $username 用户名
     * @return boolean|int
     */
    public function CheckUsername($username)
    {
        if (empty($username)) {
            $this->error = '用户名不能为空！';
            return false;
        }
        $guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
        if (!preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $username)) {
            $find = MemberUserModel::where("username", $username)->count();
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
     * 检查密码
     * @param string $email 邮箱地址
     * @return boolean
     */
    public function checkPassword($password)
    {
        if (empty($password)) {
            $this->error = '密码不能为空！';
            return false;
        }
        return true;
    }

    /**
     * 检查 Email 地址
     * @param string $email 邮箱地址
     * @return boolean
     */
    public function CheckEmail($email)
    {
        if (strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) {
            $find = MemberUserModel::where("username", $email)->count();
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
     * @param $userIds
     * @param $isBlock
     * @return bool
     */
    public static function blockUser($userIds, $isBlock)
    {
        $count = 0;
        foreach ($userIds as $userId) {
            $count += MemberUserModel::where('user_id', $userId)->save(['is_block' => $isBlock]);
        }
        if ($count > 0) {
            return true;
        }
        return false;
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
     * 注册用户登录状态
     * @param array $userInfo 用户信息
     */
    public static function registerLogin(array $userInfo)
    {
        //写入session
        $token = Encrypt::authcode((int)$userInfo['id'], '');
        session(self::userUidKey, $token);
        UserTokenModel::insert([
            'session_id' => session_id(),
            'token' => $token,
            'user_id' => (int)$userInfo['id'],
            'expire_time' => time() + 7 * 86400,
            'create_time' => time()
        ]);
        //更新状态
        self::loginStatus((int)$userInfo['id']);
        //注册权限 TODO
//        \Libs\System\RBAC::saveAccessList((int)$userInfo['id']);
    }

    /**
     * 更新登录状态信息
     * @param string $userId
     * @return boolean|array
     */
    public static function loginStatus($userId) {
        $data['last_login_time'] = time();
        $data['last_login_ip'] = get_client_ip();
        return UserModel::where('id',$userId)->save($data);
    }

}
