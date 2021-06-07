<?php
/**
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2019/12/3
 * Time: 14:50
 */

namespace app\member\libs;

/**
 * 用户通用信息
 * Class ReturnCode
 * @package app\member\libs
 */
class ReturnCode {

    const NO_LOGIN = '401';  //未登录
    const YES_BLOCK = '402';  //用户已经被拉黑了
    const NO_AUDIT = '403'; //用户未通过审核

}