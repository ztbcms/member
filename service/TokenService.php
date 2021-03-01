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
    static function encode($content, $key = '')
    {
        if(empty($key)){
            $key = Config::get('system.authcode');
        }
        return Encrypt::authcode($content, Encrypt::OPERATION_ENCODE, $key, Config::get('passport.token_expire'));
    }

    static function decode($result, $key = '')
    {
        if(empty($key)){
            $key = Config::get('system.authcode');
        }
        return Encrypt::authcode($result, Encrypt::OPERATION_DECODE, $key);
    }
}