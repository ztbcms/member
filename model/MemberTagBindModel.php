<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 9:00
 */

namespace app\member\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 用户标签关联表
 * Class TagModel
 * @package app\member\model
 */
class MemberTagBindModel extends Model
{
    protected $name = 'member_tag_bind';

    use SoftDelete;
    protected $defaultSoftDelete = 'delete_time';

}
