<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 16:14
 */

namespace app\member\model;

use app\cms\model\ModelModel;
use app\member\validate\MemberValidate;
use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * 会员
 * Class MemberUserModel
 * @package app\member\model
 */
class MemberUserModel extends Model
{
    protected $name = 'member';
    protected $pk = 'user_id';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    // 已审核
    const IS_CHECKED = 1;
    // 拉黑
    const IS_BLOCK = 1;
    // 取消审核
    const NO_CHECKED = 0;
    // 取消拉黑
    const NO_BLOCK = 0;

    protected $append = [
        'tags_name'
    ];

    /**
     * 获取用户标签名称
     * @param $val
     * @param $data
     * @return string
     */
    public function getTagsNameAttr($val, $data)
    {
        $tagIds = MemberTagBindModel::where('user_id', $data['user_id'])->column('tag_id');
        $tagName = MemberTagModel::whereIn('tag_id', $tagIds)->column('tag_name');
        return implode(',',$tagName);
    }

    /**
     * 会员配置缓存
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function member_cache() {
        $data = Db::name('module')->where('module','Member')->value('setting');
        $setting = unserialize($data);
        cache("Member_Config", $setting);
        $this->member_model_cahce();
        return $data;
    }

    /**
     * 会员模型缓存
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function member_model_cahce() {
        $data = ModelModel::getModelAll(2);
        cache("Model_Member", $data);
        return $data;
    }


//    // 添加用户
//    public function addUser($data){
//        // 校验 TODO
//        // 唯一用户名，邮箱，密码是否一致，
//
//        //进行数据验证
//        $validate = new MemberValidate();
//        if (!$validate->check($data)) {
//            $this->error = $validate->getError();
//            return false;
//        }
//        // 加密
//        $data['encrypt'] = genRandomString(6); //随机密码
//        $data['password'] = $this->encryption(0, $data['password'], $data['encrypt']);
//        // 添加
//        return self::create($data);
//    }
//
//    /**
//     * 对明文密码，进行加密，返回加密后的密码
//     * @param string $identifier 为数字时，表示uid，其他为用户名
//     * @param string $pass 明文密码，不能为空
//     * @return string 返回加密后的密码
//     */
//    public function encryption($identifier, $pass, $verify = "") {
//        $v = array();
//        if (is_numeric($identifier)) {
//            $v["id"] = $identifier;
//        } else {
//            $v["username"] = $identifier;
//        }
//        $pass = md5($pass . md5($verify));
//        return $pass;
//    }
//
//    /**
//     * 根据标识修改对应用户密码
//     * @param string $identifier
//     * @param string $password
//     * @return boolean
//     */
//    public function ChangePassword($identifier, $password) {
//        if (empty($identifier) || empty($password)) {
//            return false;
//        }
//        $term = array();
//        if (is_numeric($identifier)) {
//            $term['userid'] = $identifier;
//        } else {
//            $term['username'] = $identifier;
//        }
//        $verify = $this->where($term)->getField('verify');
//
//        $data['password'] = $this->encryption($identifier, $password, $verify);
//
//        $up = $this->where($term)->save($data);
//        if ($up) {
//            return true;
//        }
//        return false;
//    }

}
