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
 * 标签d
 * Class TagModel
 * @package app\member\model
 */
class MemberTagModel extends Model
{
    protected $name = 'member_tag';

    use SoftDelete;
    protected $defaultSoftDelete = 'delete_time';

}
