<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 10:44
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberModelModel;
use think\facade\Db;

/**
 * 会员模型
 * Class MemberModelService
 * @package app\member\service
 */
class MemberModelService extends BaseService
{
    const membershipModelSql = 'data/sql/cms_member.sql'; //会员模型

    /**
     * 获取模型详情
     * @param $modelId
     * @return array|bool|\think\Model
     */
    function getDetail($modelId)
    {
        $model = Db::name('model')->where('modelid', $modelId)->findOrEmpty();
        if (empty($model)) {
            $this->error = '模型不存在';
            return false;
        }
        return $model;
    }

    /**
     * 获取列表
     * @param array $where
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    function getList($where = [], $limit = 15){
        return Db::name('model')->where('type',2)
            ->where($where)
            ->order('modelid','desc')
            ->paginate($limit);
    }

    /**
     * 添加编辑模型
     * @param $name
     * @param $description
     * @param $tableName
     * @param int $disabled
     * @param int $modelId
     * @return bool
     * @throws \think\db\exception\DbException
     */
    function addEditModel($name, $description, $tableName, $disabled = 0, $modelId = 0)
    {
        Db::startTrans();
        $model = Db::name('model')->where('modelid', $modelId)->findOrEmpty();
        $tableName = 'member_'.$tableName;
        // 更新
        if (!empty($model)) {
            $data['name'] = $name;
            $data['description'] = $description;
            $data['disabled'] = $disabled;
            $data['addtime'] = time();
            $res = Db::name('model')->where('modelid', $modelId)->update($data);
        } else {
            // 插入
            $data['type'] = 2;
            $data['name'] = $name;
            $data['description'] = $description;
            $data['tablename'] = $tableName;
            $modelId = Db::name('model')->insertGetId($data);
            $res = $this->addModelMember($tableName, $modelId);
        }
        if ($res) {
            Db::commit();
            //更新缓存
            MemberModelModel::member_cache();
            return true;
        }
        Db::rollback();
        return false;
    }


    /**
     * 创建表
     * @param $tableName
     * @param $modelId
     * @return bool
     */
    function addModelMember($tableName, $modelId)
    {
        if (empty($tableName)) {
            return false;
        }
        //表前缀
        $dbPrefix = getDbConfig('prefix');
        //读取会员模型SQL模板
        $membershipModelSql = file_get_contents(app_path() . self::membershipModelSql);
        //表前缀，表名，模型id替换
        $sqlSplit = str_replace(array('@cms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $membershipModelSql);
        return sql_execute($sqlSplit);
    }


    /**
     * 根据模型ID删除模型
     * @param $modelId 模型id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteModel($modelId) {
        if (empty($modelId)) {
            return false;
        }
        //这里可以根据缓存获取表名
        $modelData = Db::name('model')->where("modelid" , $modelId)->find();
        if (!$modelData) {
            $this->error = '模型不存在';
            return false;
        }
        //表名
        $modelTable = $modelData['tablename'];
        //删除模型数据
        Db::name('model')->where("modelid" , $modelId)->delete();
        //更新缓存
        cache("Model", NULL);
        //删除所有和这个模型相关的字段
        Db::name('model_field')->where("modelid" , $modelId)->delete();
        //删除主表
        $this->deleteTable($modelTable);
        if ((int) $modelData['type'] == 0) {
            //删除副表
            $this->deleteTable($modelTable . "_data");
        }
        //更新缓存
        MemberModelModel::member_cache();
        return true;
    }


    /**
     * 删除表
     * @param $table string 不带表前缀
     * @return boolean
     */
    public function deleteTable($table) {
        if (table_exists($table)) {
            drop_table($table);
        }
        return true;
    }
}
