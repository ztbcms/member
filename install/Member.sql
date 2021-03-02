DROP TABLE IF EXISTS `cms_member_connect_token`;
CREATE TABLE `cms_member_connect_token` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL COMMENT '用户ID',
  `open_id` varchar(32) NOT NULL COMMENT '授权标识',
  `access_token` varchar(255) NOT NULL COMMENT 'access_token',
  `open_app_id` int(11) NOT NULL COMMENT '应用id',
  `app_type_name` varchar(255) DEFAULT NULL COMMENT '应用名称',
  `expires_in` int(10) NOT NULL COMMENT 'token过期时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`token_id`) USING BTREE,
  KEY `openid` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='登录授权';


DROP TABLE IF EXISTS `cms_member_tag`;
CREATE TABLE `cms_member_tag` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '标签id',
  `tag_name` varchar(255) DEFAULT NULL COMMENT '标签名',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `is_show` int(11) DEFAULT '1' COMMENT '是否显示',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='用户标签表';

DROP TABLE IF EXISTS `cms_member_tag_bind`;
CREATE TABLE `cms_member_tag_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `delete_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户标签关联表';

DROP TABLE IF EXISTS `cms_member_open`;
CREATE TABLE `cms_member_open` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_type` varchar(255) DEFAULT NULL COMMENT 'APP类型',
  `app_key` text,
  `app_secret` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='第三方平台管理表';


DROP TABLE IF EXISTS `cms_member`;
CREATE TABLE `cms_member` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `encrypt` varchar(6) NOT NULL DEFAULT '' COMMENT '随机码',
  `check_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态 0待审核1通过2不通过',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别,1男,2女,0未知',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '会员头像',
  `reg_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `regip` varchar(15) NOT NULL COMMENT '注册ip',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_block` int(11) DEFAULT '0' COMMENT '是否被拉黑',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20)),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员表';

DROP TABLE IF EXISTS `cms_member_bind`;
CREATE TABLE `cms_member_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `bind_type` varchar(255) DEFAULT NULL COMMENT '绑定第三方类型',
  `bind_open_id` varchar(255) DEFAULT NULL COMMENT '绑定第三方的用户 open_id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员第三方绑定表';


DROP TABLE IF EXISTS `cms_member_group`;
CREATE TABLE `cms_member_group` (
  `group_id` tinyint(3) unsigned NOT NULL auto_increment COMMENT '会员组id',
  `group_name` char(15) NOT NULL COMMENT '用户组名称',
  `issystem` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否是系统组',
  `starnum` tinyint(2) unsigned NOT NULL COMMENT '会员组星星数',
  `point` smallint(6) unsigned NOT NULL COMMENT '积分范围',
  `allowmessage` smallint(5) unsigned NOT NULL default '0' COMMENT '最大发送短消息数量',
  `allowvisit` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否允许访问',
  `allowpost` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否允许发稿',
  `allowpostverify` tinyint(1) unsigned NOT NULL COMMENT '是否投稿不需审核',
  `allowsearch` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否允许搜索',
  `allowupgrade` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否允许自主升级',
  `allowsendmessage` tinyint(1) unsigned NOT NULL COMMENT '允许发送短消息',
  `allowpostnum` smallint(5) unsigned NOT NULL default '0' COMMENT '每天允许发文章数',
  `allowattachment` tinyint(1) NOT NULL COMMENT '是否允许上传附件',
  `icon` char(255) NOT NULL COMMENT '用户组图标',
  `usernamecolor` char(7) NOT NULL COMMENT '用户名颜色',
  `description` char(100) NOT NULL COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL default '0' COMMENT '排序',
  `disabled` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否禁用',
  `expand` mediumtext NOT NULL COMMENT '扩展',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY  (`group_id`),
  KEY `disabled` (`disabled`),
  KEY `listorder` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='会员组';

-- ----------------------------
-- Records of cms_member_group
-- ----------------------------
INSERT INTO `cms_member_group` VALUES ('1', '禁止访问', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '', '', '0', '0', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('2', '新手上路', '1', '1', '50', '100', '1', '1', '0', '1', '0', '1', '0', '0', '', '', '', '2', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('6', '注册会员', '1', '2', '100', '150', '0', '1', '0', '1', '1', '1', '0', '1', '', '', '', '6', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('4', '中级会员', '1', '3', '150', '500', '1', '1', '0', '1', '1', '1', '0', '1', '', '', '', '4', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('5', '高级会员', '1', '5', '300', '999', '1', '1', '1', '1', '1', '1', '0', '1', '', '', '', '5', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('7', '邮件认证', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '', '', '', '7', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('8', '游客', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '', '', '', '0', '0', '', '0', '0', '0');

-- ----------------------------
-- Records of cms_member_open
-- ----------------------------
INSERT INTO `cms_member_open` VALUES (1, 'weibo', '', '', 1604889574, 0, NULL);
INSERT INTO `cms_member_open` VALUES (2, 'qq', '', '', 1604889574, 0, NULL);


