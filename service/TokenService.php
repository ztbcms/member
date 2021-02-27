<?php
/**
 * Author: jayinton
 */

namespace app\member\service;


use app\common\service\BaseService;
use app\common\util\Encrypt;
use think\facade\Config;

class TokenService extends BaseService
{
    static function encode($content)
    {
        return Encrypt::authcode($content, Encrypt::OPERATION_ENCODE, Config::get('system.authcode'), Config::get('passport.token_expire'));
    }

    static function decode($result)
    {
        return Encrypt::authcode($result, Encrypt::OPERATION_DECODE, Config::get('system.authcode'));
    }
}