<?php
/**
 * Author: cycle_3
 */

namespace app\member\libs;

/**
 * 通用方法
 * Class Uiversal
 * @package app\member\libs
 */
class Uiversal
{

    /**
     * 产生一个指定长度的随机字符串,并返回给用户
     * @param int $len 产生字符串的长度
     * @return string 随机字符串
     */
    public function genRandomString($len = 6)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9",
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 获取全局唯一标识符
     * @param bool $trim
     * @return string
     */
    public function getGuidV4($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            $charid = com_create_guid();
            return $trim == true ? trim($charid, '{}') : $charid;
        }
        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace .
            substr($charid, 0, 8) . $hyphen .
            substr($charid, 8, 4) . $hyphen .
            substr($charid, 12, 4) . $hyphen .
            substr($charid, 16, 4) . $hyphen .
            substr($charid, 20, 12) .
            $rbrace;
        return $guidv4;
    }
}