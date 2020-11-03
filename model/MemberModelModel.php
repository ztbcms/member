<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 13:47
 */

namespace app\member\model;

use think\facade\Db;
use think\Model;

/**
 * 会员模型
 * Class MemberModelModel
 * @package app\member\model
 */
class MemberModelModel extends Model
{
    protected $name = 'model';

    /**
     * 获取模型中的字段
     * @param $modelId
     * @param $userId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getModelFields($modelId, $userId = 0)
    {
        if ($userId > 0) {
            // 查询表字段
            $tableName = MemberModelModel::where('modelid', $modelId)->value('tablename');
            $data = Db::name(ucwords($tableName))->where('userid', $userId)->find();

            $fieldsArr = array_keys($data);
            $fieldsNames = Db::name('model_field')->where('modelid', $modelId)->column('name', 'field');

            $fields = [];
            for ($i = 0; $i < count($data); $i++) {
                if(empty($fieldsNames[$fieldsArr[$i]])) continue;
                $fields[] = [
                    'name'  => $fieldsNames[$fieldsArr[$i]],
                    'field' => $fieldsArr[$i],
                    'value' => $data[$fieldsArr[$i]],
                ];
            }
            return $fields;
        }
        $fields = Db::name('model_field')->where('modelid', $modelId)->field('field,name')->select();
        $data = [];
        foreach ($fields as $item) {
            $data[] = [
                'name'  => $item['name'],
                'field' => $item['field'],
                'value' => '',
            ];
        }
        return $data;
    }

    /**
     * 根据模型类型取得数据用于缓存
     * @param null $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getModelAll($type = null)
    {
        $where = array('disabled' => 0);
        if (!is_null($type)) {
            $where['type'] = $type;
        }
        $data = self::where($where)->select();
        $Cache = array();
        foreach ($data as $v) {
            $Cache[$v['modelid']] = $v;
        }
        return $Cache;
    }

    /**
     * 会员配置缓存
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function member_cache()
    {
        $setting = Db::name('module')->where('module', 'Member')->value('setting');
        $data = unserialize($setting);
        cache("Member_Config", $data);
        self::member_model_cahce(true);
        return $data;
    }

    /**
     * 会员模型缓存
     * @param $isForce
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function member_model_cahce($isForce = false)
    {
        if (!$isForce && cache("Model_Member")) {
            return cache("Model_Member");
        }
        $data = self::getModelAll(2);
        cache("Model_Member", $data);
        return $data;
    }

}
