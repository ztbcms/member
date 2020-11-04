<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 15:23
 */

namespace app\member\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 第三方授权记录
 * Class MemberConnectTokenModel
 * @package app\member\model
 */
class MemberConnectTokenModel extends Model
{
    protected $name = 'member_connect_token';
    protected $pk = 'token_id';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 获取是否过期
     * @param $val
     * @return array
     */
    public function getExpiresInAttr($val)
    {
        return [
            'text'  => ($val < time()) ? '是' : '否',
            'value' => $val
        ];
    }

    /**
     * 获取用户名称
     * @return \think\model\relation\HasOne
     */
    public function userName()
    {
        return $this->hasOne(MemberUserModel::class, 'user_id', 'uid')
            ->bind([
                'nickname',
                'username',
            ]);
    }

    /**
     * 根据授权信息，取得对应绑定的用户ID
     * @param string $openid
     * @param string $appType
     * @return boolean
     */
    public function getUserid($openid, $appType)
    {
        if (empty($openid) || empty($appType)) {
            return false;
        }
        return $this->where("open_id", $openid)
            ->where("app_type", $appType)
            ->value("uid");
    }

    /**
     * 更新token
     * @param $openid
     * @param $appType
     * @param $accessToken
     * @param $expires_in
     * @return bool
     */
    public function updateTokenTime($openid, $appType, $accessToken, $expires_in)
    {
        return $this->where("open_id", $openid)
            ->where("app_type", $appType)
            ->save(['access_token' => $accessToken, 'expires_in' => $expires_in]);
    }
}
