<?php
/**
 * Created by FHYI.
 * Date 2020/11/2
 * Time 17:56
 */

namespace app\member\controller;

use app\common\controller\AdminController;
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
    public function index()
    {
        return View::fetch();
    }

    // 获取更新
    public function setting()
    {
        if ($this->request->isPost()) {
            $setting = $_POST['setting'];
            $data['setting'] = serialize($setting);
            $Module = M('Module');
            if ($Module->create()) {
                if ($Module->where(array("module" => "Member"))->save($data) !== false) {
                    $this->member->member_cache();
                    $this->success("更新成功！", U("Setting/setting"));
                } else {
                    $this->error("更新失败！", U("Setting/setting"));
                }
            } else {
                $this->error($Module->getError());
            }
        } else {
            //取得会员接口信息
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
            $setting = Db::name('module')->where('module','Member')->value('setting');
            echo json_encode($setting);die();
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
            $this->assign('groupCache', $groupCache);
//            $this->assign('groupsModel', $groupsModel);
            $this->assign("setting", unserialize($setting));
//            $this->assign("Interface", $Interface);
            $this->display();
        }
    }

    public function updateSetting(){

    }
}
