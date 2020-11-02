SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for cms_connect
-- ----------------------------
DROP TABLE IF EXISTS `cms_connect`;
CREATE TABLE `cms_connect` (
  `connectid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `openid` varchar(32) NOT NULL COMMENT '授权标识',
  `uid` mediumint(8) NOT NULL COMMENT '用户ID',
  `app` varchar(10) NOT NULL COMMENT '应用名称',
  `accesstoken` char(50) NOT NULL COMMENT 'access_token',
  `expires` int(10) NOT NULL COMMENT 'token过期时间',
  PRIMARY KEY (`connectid`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='登录授权';

-- ----------------------------
-- Table structure for cms_member_tag
-- ----------------------------
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户标签表';

-- ----------------------------
-- Table structure for cms_member_tag_bind
-- ----------------------------
CREATE TABLE `ztb_member_tag_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `delete_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户标签关联表';


-- ----------------------------
-- Table structure for `cms_member`
-- ----------------------------
DROP TABLE IF EXISTS `cms_member`;
CREATE TABLE `cms_member` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `encrypt` varchar(6) NOT NULL DEFAULT '' COMMENT '随机码',
  `checked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别,1男,2女,0未知',
  `about` varchar(255) NOT NULL COMMENT '个人介绍',
  `heat` int(11) NOT NULL DEFAULT '0' COMMENT '空间热度',
  `theme` char(11) NOT NULL DEFAULT '' COMMENT '空间主题名称',
  `praise` int(11) NOT NULL DEFAULT '0' COMMENT '被赞数',
  `attention` int(11) NOT NULL DEFAULT '0' COMMENT '关注数',
  `fans` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `share` int(11) NOT NULL DEFAULT '0' COMMENT '分享数',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `userpic` varchar(200) NOT NULL COMMENT '会员头像',
  `reg_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `regip` varchar(15) NOT NULL DEFAULT '' COMMENT '注册ip',
  `lastip` varchar(15) NOT NULL DEFAULT '' COMMENT '上次登录ip',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `areaid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '地区id',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '钱金总额',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有短消息',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否锁定',
  `vip` tinyint(1) NOT NULL COMMENT 'vip等级',
  `overduedate` int(10) NOT NULL COMMENT 'vip过期时间',
  `is_block` int(11) DEFAULT '0' COMMENT '是否被拉黑',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20)),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Table structure for `cms_member_favorite`
-- ----------------------------
DROP TABLE IF EXISTS `cms_member_favorite`;
CREATE TABLE `cms_member_favorite` (
  `fid` int(11) NOT NULL auto_increment COMMENT '收藏ID',
  `userid` mediumint(9) NOT NULL default '0' COMMENT '用户UID',
  `modelid` smallint(6) NOT NULL default '0' COMMENT '模型ID',
  `catid` smallint(6) NOT NULL default '0' COMMENT '栏目ID',
  `id` mediumint(9) NOT NULL default '0' COMMENT '信息ID',
  `title` varchar(255) NOT NULL COMMENT '收藏标题',
  `url` char(255) default NULL COMMENT '信息地址',
  `datetime` int(11) NOT NULL COMMENT '添加时间戳',
  PRIMARY KEY  (`fid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员收藏表';

-- ----------------------------
-- Table structure for `cms_member_online`
-- ----------------------------
DROP TABLE IF EXISTS `cms_member_online`;
CREATE TABLE `cms_member_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` mediumint(9) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` char(30) NOT NULL COMMENT '用户名',
  `lasttime` int(10) DEFAULT NULL COMMENT '最后操作时间戳',
  PRIMARY KEY (`id`),
  KEY `userid`  USING HASH (`userid`),
  KEY `lasttime` (`userid`,`lasttime`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='在线用户表';

-- ----------------------------
-- Table structure for `cms_member_content`
-- ----------------------------
DROP TABLE IF EXISTS `cms_member_content`;
CREATE TABLE `cms_member_content` (
  `id` int(10) NOT NULL auto_increment,
  `catid` smallint(5) NOT NULL COMMENT '栏目ID',
  `content_id` int(10) NOT NULL COMMENT '信息ID',
  `userid` mediumint(8) NOT NULL COMMENT '会员ID',
  `integral` tinyint(1) NOT NULL COMMENT '是否赠送过点数',
  `status` tinyint(1) NOT NULL COMMENT '审核状态',
  `time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY  (`id`),
  KEY `userid` (`catid`,`content_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员投稿信息记录表';

-- ----------------------------
-- Table structure for `cms_member_group`
-- ----------------------------
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员组';

-- ----------------------------
-- Records of cms_member_group
-- ----------------------------
INSERT INTO `cms_member_group` VALUES ('1', '禁止访问', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '', '', '0', '0', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('2', '新手上路', '1', '1', '50', '100', '1', '1', '0', '1', '0', '1', '0', '0', '', '', '', '2', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('6', '注册会员', '1', '2', '100', '150', '0', '1', '0', '1', '1', '1', '0', '1', '', '', '', '6', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('4', '中级会员', '1', '3', '150', '500', '1', '1', '0', '1', '1', '1', '0', '1', '', '', '', '4', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('5', '高级会员', '1', '5', '300', '999', '1', '1', '1', '1', '1', '1', '0', '1', '', '', '', '5', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('7', '邮件认证', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', 'images/group/vip.jpg', '#000000', '', '7', '0', '', '0', '0', '0');
INSERT INTO `cms_member_group` VALUES ('8', '游客', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '', '', '', '0', '0', '', '0', '0', '0');

