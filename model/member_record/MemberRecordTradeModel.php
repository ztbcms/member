<?php
/**
 * Author: cycle_3
 */

namespace app\member\model\member_record;

use think\Model;
use think\model\concern\SoftDelete;

class MemberRecordTradeModel extends Model
{

    protected $name = 'member_record_trade';
    protected $pk = 'id';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

}