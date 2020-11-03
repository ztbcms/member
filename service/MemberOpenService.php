<?php
/**
 * Created by FHYI.
 * Date 2020/11/3
 * Time 9:52
 */

namespace app\member\service;

use app\common\service\BaseService;
use app\member\model\MemberOpenModel;

/**
 * 第三方平台绑定
 * Class MemberOpenService
 * @package app\member\service
 */
class MemberOpenService extends BaseService
{
    public function getDetail($id)
    {
        $data = MemberOpenModel::where('id', $id)->findOrEmpty();
        if ($data->isEmpty()) {
            $this->error = '信息不存在';
            return false;
        }
        return $data;
    }

    /**
     * 获取列表
     * @param array $where
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList($where = [], $limit = 15)
    {
        return MemberOpenModel::where($where)->paginate($limit);
    }

    /**
     * 添加编辑应用
     * @param $appType
     * @param $appKey
     * @param $appSecret
     * @param int $appId
     * @return bool
     */
    public function addEditApp($appType, $appKey, $appSecret, $appId = 0)
    {
        $model = MemberOpenModel::where('id', $appId)->findOrEmpty();
        $model->app_type = $appType;
        $model->app_key = $appKey;
        $model->app_secret = $appSecret;
        return $model->save();
    }

    /**
     * 删除应用
     * @param $appId
     * @return bool
     */
    function deleteApp($appId)
    {
        $model = MemberOpenModel::where('id', $appId)->findOrEmpty();
        if ($model->isEmpty()) {
            $this->error = '信息不存在';
            return false;
        }
        $res = $model->delete();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}
