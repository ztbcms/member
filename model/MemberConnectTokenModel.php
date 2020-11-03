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
     * @param $data
     * @return array
     */
    public function getExpiresInAttr($val,$data){
        $endTime = $data['create_time'] + $val;
        return [
            'text' => ($endTime < time()) ? '是' : '否',
            'value' => $val
        ];
    }

    /**
     * 获取用户名称
     * @return \think\model\relation\HasOne
     */
    public function userName(){
        return $this->hasOne(MemberUserModel::class,'user_id','uid')
            ->bind([
                'nickname',
                'username',
            ]);
    }
}
