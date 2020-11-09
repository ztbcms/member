<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 18:01
 */

namespace app\member\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 会员组
 * Class MemberGroupModel
 * @package app\member\model
 */
class MemberGroupModel extends Model
{
    protected $name = 'member_group';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;


    /**
     * 获取用户权限
     * @param $data
     * @return array
     */
    public static function getPowerData($data)
    {
        $power = [];
        if($data['allowpostverify']) $power[] = 'allowpostverify';
        if($data['allowupgrade']) $power[] = 'allowupgrade';
        if($data['allowsendmessage']) $power[] = 'allowsendmessage';
        if($data['allowpost']) $power[] = 'allowpost';
        if($data['allowattachment']) $power[] = 'allowattachment';
        if($data['allowsearch']) $power[] = 'allowsearch';
        return $power;
    }

    /**
     * 获取权限
     * @param $data
     * @return array
     */
    public static function getExpandData($data){
        $expand = unserialize($data['expand']);
        if(!$expand) {
            $expand = [
                'upphotomax' => '0',
                'iswall' => '0',
                'ismsg' => '0',
                'isrelatio' => '0',
                'isfavorite' => '0',
                'isweibo' => '0'
            ];
        }
        return $expand;
    }

}
