说明：
1、本模块为会员模块，提供会员管理功能，具体有：
   会员列表，会员组，会员模型，登录授权token(基于jwt)，用户统计（新增、活跃度）

2、目录：/member/controller/api 中提供了示例接口：
    列举了较常用的方法：创建用户，进行第三方平台绑定，通过第三方进行登录
    
#### 权限介绍

直接继承 app\member\controller\api\Base.php 即可获得登录权限
具体的权限控制可以在中间件 app\member\middleware\Authority.php 中进行

#### 对外接口

|接口|说明|
|-|-|-|
|/member/api.User/login|登录|
|/member/api.User/register|创建会员|

#### 微信板块 (依赖wechat模块)

|接口|说明|
|-|-|-|
|/member/api.WeChat/getUserWeChatPhone|获取微信小程序手机号登录|
|/member/api.WeChat/getUserWeChat|获取微信小程序授权登录|


#### 文档说明 （具体调用可参照文档说明）

https://www.apizza.net/project/e939c46895885fa81c56cd46b92e0be8/browse



