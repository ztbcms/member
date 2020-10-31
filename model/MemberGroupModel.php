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


}
