<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 18:19
 */

/**
 * 产生一个指定长度的随机字符串,并返回给用户
 * @param int $len 产生字符串的长度
 * @return string 随机字符串
 */
function genRandomString($len = 6) {
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
 * 获取模型数据
 * @param string $modelId 模型ID
 * @param string $field 返回的字段，默认返回全部，数组
 * @return boolean|string
 */
function getModel($modelId, $field = '') {
    if (empty($modelId)) {
        return false;
    }
    $key = 'getModel_' . $modelId;
    $cache = cache($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = \think\facade\Db::name('model')->where('modelid' , $modelId)->findOrEmpty();
        if ($cache->isEmpty()) {
            cache($key, 'false', 60);
            return false;
        } else {
            cache($key, $cache, 3600);
        }
    }
    if ($field) {
        return $cache[$field];
    } else {
        return $cache;
    }
}
