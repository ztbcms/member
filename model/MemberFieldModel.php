<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 9:02
 */

namespace app\member\model;

use think\facade\Db;
use think\Model;

/**
 * 会员模型字段管理
 * Class MemberFieldModel
 * @package app\member\model
 */
class MemberFieldModel extends MemberBaseModel
{
    protected $name = 'model_field';

    //禁止被禁用（隐藏）的字段列表（字段名）
    public $forbid_fields = array(/*'catid',  'title' , 'updatetime', 'inputtime', 'url', 'listorder', 'status', 'template', 'username', 'allow_comment', 'tags' */);

    /**
     * 根据模型ID读取全部字段信息
     * @param $modelId
     * @return mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getModelField($modelId)
    {
        return $this->where("modelid", $modelId)->order("listorder", "ASC")->select();
    }


    /**
     * 删除字段
     * @param $fieldId
     * @return array|bool
     */
    public function doDelete($fieldId)
    {
        //原字段信息
        $info = $this->where("fieldid", $fieldId)->findOrEmpty();
        if ($info->isEmpty()) {
            return ['status' => false, 'msg' => '该字段不存在'];
        }
        //模型id
        $modelid = $info['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $info['issystem']);
        if (!$this->table_exists($tablename)) {
            return ['status' => false, 'msg' => '数据表不存在'];
        }
        //判断是否允许删除
        if (false === $this->isDelField($info['field'])) {
            return ['status' => false, 'msg' => '该字段不允许被删除'];
        }

        $res = $this->deleteFieldSql($info['field'], getDbConfig('prefix') . $tablename);
        if ($res['status']) {
            $this->where('fieldid', $fieldId)
                ->where('modelid', $modelid)
                ->delete();
            return true;
        } else {
            return ['status' => false, 'msg' => $res['msg']];
        }
    }

    /**
     * 根据模型ID，返回表名
     * @param string $modelid
     * @param string|int $issystem
     * @return string
     */
    protected function getModelTableName($modelid, $issystem = 1)
    {
        //读取模型配置 以后优化缓存形式
        $model_cache = MemberModelModel::model_cache();
        //表名获取
        $model_table = $model_cache[$modelid]['tablename'];
        //完整表名获取 判断主表 还是副表
        $tablename = $issystem ? $model_table : $model_table . "_data";
        return $tablename;
    }


    /**
     * 根据字段类型，删除对应的字段到相应表里面
     * @param string $filename 字段名称
     * @param string $tablename 完整表名
     * @return boolean
     */
    protected function deleteFieldSql($filename, $tablename)
    {
        //不带表前缀的表名
        $noprefixTablename = str_replace(getDbConfig('prefix'), '', $tablename);
        if (empty($tablename) || empty($filename)) {
            return ['status' => false, 'msg' => '表名或者字段名不能为空'];
        }

        if (false === $this->table_exists($noprefixTablename)) {
            return ['status' => false, 'msg' => '该表不存在'];
        }
        switch ($filename) {
            case 'readpoint': //特殊字段类型
                $sql = "ALTER TABLE `{$tablename}` DROP `readpoint`;";
                if (false === Db::execute($sql)) {
                    return ['status' => false, 'msg' => '字段删除失败'];
                }
                break;
            //特殊自定义字段
            case 'pages':
                if ($this->field_exists($noprefixTablename, "paginationtype")) {
                    Db::execute("ALTER TABLE `{$tablename}` DROP `paginationtype`;");
                }
                if ($this->field_exists($noprefixTablename, "maxcharperpage")) {
                    Db::execute("ALTER TABLE `{$tablename}` DROP `maxcharperpage`;");
                }
                break;
            default:
                $sql = "ALTER TABLE `{$tablename}` DROP `{$filename}`;";
                if (false === Db::execute($sql)) {
                    return ['status' => false, 'msg' => '字段删除失败'];
                }
                break;
        }
        return true;
    }

}
