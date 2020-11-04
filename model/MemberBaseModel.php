<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 9:21
 */

namespace app\member\model;

use think\Model;

/**
 * 会员基类模型
 * Class MemberBaseModel
 * @package app\member\model
 */
class MemberBaseModel extends Model
{
    //禁止被删除的字段列表（字段名）
    public $forbid_delete = array(/*'catid', 'typeid', 'title', 'thumb', 'keyword', 'keywords', 'updatetime', 'tags', 'inputtime', 'posid', 'url', 'listorder', 'status', 'template', 'username', 'allow_comment'*/);

    /**
     * 验证表是否存在
     * @param $tableName
     * @return bool
     */
    function table_exists($tableName)
    {
        $tables = list_tables();
        return in_array(getDbConfig('prefix') . $tableName, $tables) ? true : false;
    }

    /**
     * 检查字段是否存在
     * $table 不带表前缀
     * @param $table
     * @param $field
     * @return bool
     */
    function field_exists($table, $field)
    {
        $fields = get_fields($table);
        return array_key_exists($field, $fields);
    }

    /**
     * 判断字段是否允许删除
     * @param string $field 字段名称
     * @return boolean
     */
    public function isDelField($field)
    {
        //禁止被删除的字段列表（字段名）
        if (in_array($field, $this->forbid_delete)) {
            return false;
        }
        return true;
    }
}
