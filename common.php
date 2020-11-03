<?php
/**
 * Created by FHYI.
 * Date 2020/10/31
 * Time 18:19
 */


/**
 * 获取数据库配置
 * @param $key
 * @return mixed
 */
function getDbConfig($key = null)
{
    $config = \think\facade\Config::get('database');
    if (!empty($key)) {
        $config = $config['connections'][$config['default']];
        return $config[$key];
    }
    return $config['connections'][$config['default']];
}


/**
 * 产生一个指定长度的随机字符串,并返回给用户
 * @param int $len 产生字符串的长度
 * @return string 随机字符串
 */
function genRandomString($len = 6)
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
 * 获取模型数据
 * @param string $modelId 模型ID
 * @param string $field 返回的字段，默认返回全部，数组
 * @return boolean|string
 */
function getModel($modelId, $field = '')
{
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
        $cache = \think\facade\Db::name('model')->where('modelid', $modelId)->findOrEmpty();
        if (empty($cache)) {
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

/**
 * 执行SQL
 * @param string $sqls SQL语句
 * @return boolean
 */
function sql_execute($sqls)
{
    $sqls = sql_split($sqls);
    if (is_array($sqls)) {
        foreach ($sqls as $sql) {
            if (trim($sql) != '') {
                \think\facade\Db::execute($sql);
            }
        }
    } else {
        \think\facade\Db::execute($sqls);
    }
    return true;
}

/**
 * SQL语句预处理
 * @param string $sql
 * @return array
 */
function sql_split($sql)
{
    $dbConfig = getDbConfig();
    if ($dbConfig['charset']) {
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=" . $dbConfig['charset'], $sql);
    }
    if ($dbConfig['prefix'] != "cms_") {
        $sql = str_replace("cms_", $dbConfig['prefix'], $sql);
    }
    $sql = str_replace("\r", "\n", $sql);
    $ret = array();
    $num = 0;
    $queriesarray = explode(";\n", trim($sql));
    unset($sql);
    foreach ($queriesarray as $query) {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        $queries = array_filter($queries);
        foreach ($queries as $query1) {
            $str1 = substr($query1, 0, 1);
            if ($str1 != '#' && $str1 != '-') {
                $ret[$num] .= $query1;
            }

        }
        $num++;
    }
    return $ret;
}


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
 * 读取全部表名
 * @return array
 */
function list_tables()
{
    $tables = array();
    $data = \think\facade\Db::query("SHOW TABLES");
    foreach ($data as $k => $v) {
        $tables[] = $v['Tables_in_' . getDbConfig('database')];
    }
    return $tables;
}


/**
 * 获取表字段
 * $table 不带表前缀
 * @param $table
 * @return array
 */
function get_fields($table)
{
    $fields = array();
    $table = getDbConfig('prefix') . $table;
    $data = \think\facade\Db::query("SHOW COLUMNS FROM $table");
    foreach ($data as $v) {
        $fields[$v['Field']] = $v['Type'];
    }
    return $fields;
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
 * 删除表
 * @param string $tableName 不带表前缀的表名
 * @return mixed
 */
function drop_table($tableName)
{
    $tableName = getDbConfig('prefix') . $tableName;
    return \think\facade\Db::execute("DROP TABLE `$tableName`");
}
