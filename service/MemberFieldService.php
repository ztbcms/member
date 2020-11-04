<?php
/**
 * Created by FHYI.
 * Date 2020/11/4
 * Time 9:14
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberFieldModel;
use think\facade\Db;

/**
 * 会员模型字段
 * Class MemberFieldService
 * @package app\member\service
 */
class MemberFieldService extends BaseService
{
    /**
     * 获取模型字段
     * @param $modelId
     * @return bool|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getModelFields($modelId)
    {
        $model = Db::name('model')->where('modelid', $modelId)->findOrEmpty();
        if (empty($model)) {
            $this->error = '该模型不存在';
            return false;
        }
        //根据模型读取字段列表
        $MemberFieldModel = new MemberFieldModel();
        $data = $MemberFieldModel->getModelField($modelId);
        return $data;
    }

    /**
     * 删除字段
     * @param $fieldIds
     * @return bool
     */
    public function delFields($fieldIds)
    {
        Db::startTrans();
        $MemberFieldModel = new MemberFieldModel();
        foreach ($fieldIds as $index => $fieldid) {
            $res = $MemberFieldModel->doDelete($fieldid);
            if (!$res['status']) {
                Db::rollback();
                $this->error = $res['msg'];
                return false;
            }
        }
        Db::commit();
        return true;
    }

    /**
     * 排序
     * @param $postData
     * @return mixed
     */
    public function listOrder($postData)
    {
        $MemberFieldModel = new MemberFieldModel();
        return $MemberFieldModel->transaction(function () use ($postData) {
            foreach ($postData['data'] as $item) {
                MemberFieldModel::where('fieldid', $item['fieldid'])->save(['listorder' => $item['listorder']]);
            }
            return true;
        });
    }

    /**
     * 批量启用。禁用
     * @param $fieldIds
     * @param $disabled
     * @return bool
     */
    public function disabled($fieldIds, $disabled)
    {
        Db::startTrans();
        $count = 0;
        foreach ($fieldIds as $fieldId) {
            $result = $this->doDisable($fieldId, $disabled);
            if ($result['status']) {
                $count++;
            }
        }
        if ($count > 0) {
            Db::commit();
            return true;
        } else {
            Db::rollback();
            $this->error = $result['msg'];
            return false;
        }
    }

    /**
     * 隐藏/启用字段
     * @param int $fieldId
     * @param int $disabled 1 禁用 0启用
     * @return array
     */
    private function doDisable($fieldId = 0, $disabled = 0)
    {
        $MemberFieldModel = new MemberFieldModel();
        $field = $MemberFieldModel->where('fieldid', $fieldId)->findOrEmpty();
        if ($field->isEmpty()) {
            return [
                'status' => false,
                'msg'    => '该字段不存在'
            ];
        }
        //检查是否允许被删除
        if (in_array($field['field'], $MemberFieldModel->forbid_fields)) {
            return [
                'status' => false,
                'msg'    => '该字段不允许被禁用'
            ];
        }

        $status = $MemberFieldModel->where('fieldid', $fieldId)->save(['disabled' => $disabled]);
        if ($status) {
            return [
                'status' => true,
                'msg'    => '操作成功'
            ];
        } else {
            return [
                'status' => false,
                'msg'    => '操作失败'
            ];
        }
    }


}
