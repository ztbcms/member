<?php
/**
 * Author: jayinton
 */

namespace app\member\cronscript;

use app\common\cronscript\CronScript;
use app\member\model\MemberTokenModel;

/**
 * 删除过期的access_token （建议每日执行一次）
 */
class DeleteExpiredAccessToken extends CronScript
{

    public function run($cronId)
    {
        $amount = MemberTokenModel::where('expires_in', '<', time())->delete();
        return self::createReturn(true, ['delete_amount' => $amount]);
    }
}