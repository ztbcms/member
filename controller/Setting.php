<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 17:56
 */

namespace app\member\controller;

use app\common\controller\AdminController;
use app\member\model\MemberUserModel;
use app\member\service\MemberGroupService;
use think\facade\Db;
use think\facade\View;

/**
 * 设置
 * Class Setting
 * @package app\member\controller
 */
class Setting extends AdminController
{
    /**
     * 主页
     * @return string
     */
    public function index()
    {
        return View::fetch();
    }


    /**
     * 获取更新
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSetting()
    {
//            //取得会员接口信息 TODO
//            $Dir = new \Dir(PROJECT_PATH . 'Libs/Driver/Passport/');
//            $Interface = array(
//                '' => '请选择帐号通行证接口（默认本地）',
//            );
//            $lan = array(
//                'Local' => '本地用户通行证',
//                'Ucenter' => 'Ucenter用户通行证',
//            );
//            foreach ($Dir->toArray() as $r) {
//                $neme = str_replace(array('Passport', '.class.php'), '', $r['filename']);
//                $Interface[$neme] = $lan[$neme] ? $lan[$neme] : $neme;
//            }

        // 模型
        $setting = Db::name('module')->where('module', 'Member')->value('setting');
        // 会员组
        $groupList = MemberGroupService::getGroupList();
        foreach ($groupList as $group) {
            if (in_array($group['group_id'], array(8, 1, 7))) {
                continue;
            }
            $groupCache[$group['group_id']] = $group['group_name'];
        }

        // 会员模型列表 TODO
//            foreach ($this->groupsModel as $m) {
//                $groupsModel[$m['modelid']] = $m['name'];
//            }

        return self::makeJsonReturn(true, unserialize($setting));

    }

    /**
     * 更新系统配置
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateSetting()
    {
        if ($this->request->isPost()) {
            $setting = $this->request->post();
            $data['setting'] = serialize($setting);
            $Module = Db::name('module');
            $res = $Module->where('module', 'Member')->save($data);
            if ($res) {
                // 更新会员配置缓存
                (new MemberUserModel)->member_cache();
                return self::makeJsonReturn(true, [], '更新成功');
            } else {
                return self::makeJsonReturn(false, [], '更新失败');
            }
        }
    }
}
