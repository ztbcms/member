####使用前请安装

#####2、使用会员模型，需安装CMS模型管理模块（会员模型的字段管理需进一步优化）


说明：
1、本模块为会员模块，提供会员管理功能，具体有：
   会员列表，会员组，会员模型，登录授权token

2、开发者可进一步扩展，提供了第三方应用授权登录。

3、目录：/member/controller/api 中提供了示例接口：
    列举了较常用的方法：创建用户，进行第三方平台绑定，通过第三方进行登录

新增表：
1、标签关联表 member_tag_bind
2、标签表  member_tag
3、第三方平台应用表 member_open
4、第三方平台登录token记录表 member_connect_token
5、第三方平台绑定用户表  member_bind

提一个bug~
在安装模块时，不会写入 setting.inc.php 文件。

#### 权限介绍

~~~~php

 直接继承 app\member\controller\api\Base.php 即可获得登录权限
 具体的权限控制可以在中间件 app\member\middleware\Authority.php 中进行

~~~~

#### 对外接口

|接口|说明|
|-|-|-|
|{{host}}/member/api.User/login|登录|
|{{host}}/member/api.User/register|创建会员|

#### 微信板块 (依赖wechat模块)

|接口|说明|
|-|-|-|
|{{host}}/member/api.WeChat/getUserWeChatPhone|获取微信小程序手机号登录|
|{{host}}/member/api.WeChat/getUserWeChat|获取微信小程序授权登录|



