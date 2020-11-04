<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 18:07
 */

namespace app\member\event;

use think\facade\Db;

class Test
{
    public $data;

    public function __construct($data)
    {
        $data['event'] = json_encode($data);
        return Db::name('local_log')->insert($data);
    }

    public function handle($data)
    {
        // 事件监听处理
        $data['event'] = json_encode($data);
        return Db::name('local_log')->insert($data);
    }
}
