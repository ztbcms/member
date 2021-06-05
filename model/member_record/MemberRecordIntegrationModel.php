<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\member_record;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 用户积分记录
 * Class MemberRecordIntegrationModel
 * @package app\member\model\member_record
 */
class MemberRecordIntegrationModel extends Model
{

    protected $name = 'member_record_integration';
    protected $pk = 'id';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

}