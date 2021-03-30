DROP TABLE IF EXISTS `cms_member`;
CREATE TABLE `cms_member` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `encrypt` varchar(6) NOT NULL DEFAULT '' COMMENT '随机码',
  `audit_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态 0待审核1通过2不通过',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别,1男,2女,0未知',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '会员头像',
  `reg_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` varchar(32) NOT NULL COMMENT '注册ip',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `is_block` int(11) DEFAULT '0' COMMENT '是否被拉黑',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `role_id` int(11) DEFAULT '0' COMMENT '角色',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20)),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员表';

DROP TABLE IF EXISTS `cms_member_role`;
CREATE TABLE `cms_member_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用状态 0禁用 1启用',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` int(3) NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色信息列表';

DROP TABLE IF EXISTS `cms_member_oauth_client`;
CREATE TABLE `cms_member_oauth_client` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_key` varchar (255),
  `app_secret` varchar (255),
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='授权客户端';

DROP TABLE IF EXISTS `cms_member_oauth_token`;
CREATE TABLE `cms_member_oauth_token` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL COMMENT '用户ID',
  `access_token` varchar(255) NOT NULL COMMENT 'access_token',
  `client_id` int(11) NOT NULL COMMENT '所属客户端ID',
  `expires_in` int(10) NOT NULL COMMENT '过期时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`token_id`) USING BTREE,
  KEY `openid` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='令牌';



