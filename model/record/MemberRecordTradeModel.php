<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\record;

use think\Model;
use think\model\concern\SoftDelete;

class MemberRecordTradeModel extends Model
{
    use SoftDelete;

    protected $name = 'member_record_trade';
    protected $pk = 'id';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

}