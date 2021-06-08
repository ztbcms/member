#### 权限介绍

直接继承 app\member\controller\api\MemberBase.php 即可获得登录权限

具体的权限控制可以在中间件 app\member\middleware\MemberAuthority.php 中进行修改

#### 微信板块 (依赖wechat模块)

|接口|说明|
|-|-|-|
|/member/api.WeChat/getUserWeChatPhone|获取微信小程序手机号登录|
|/member/api.WeChat/getUserWeChat|更新用户信息|

#### 文档说明（具体调用可参照文档说明）

https://www.apizza.net/project/e939c46895885fa81c56cd46b92e0be8/browse



