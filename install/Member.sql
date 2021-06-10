DROP TABLE IF EXISTS `cms_member`;
CREATE TABLE `cms_member`
(
    `user_id`      int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户id',
    `username`     varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '用户名',
    `password`     varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '密码',
    `encrypt`      varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci   NOT NULL DEFAULT '' COMMENT '随机码',
    `audit_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '审核状态 0待审核1通过2不通过',
    `sex`          tinyint(4) NOT NULL DEFAULT 0 COMMENT '性别,1男,2女,0未知',
    `nickname`     varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '昵称',
    `avatar`       varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员头像',
    `reg_time`     int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
    `reg_ip`       varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '注册ip',
    `email`        varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
    `phone`        varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '手机号码',
    `is_block`     int(11) NULL DEFAULT 0 COMMENT '是否被拉黑',
    `update_time`  int(11) NULL DEFAULT 0 COMMENT '更新时间',
    `role_id`      int(11) NULL DEFAULT 0 COMMENT '角色',
    `source`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户来源',
    `source_type`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户来源类型 如 open_id',
    `grade_id`     int(11) UNSIGNED NULL DEFAULT 0 COMMENT '用户等级',
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`),
    KEY            `email` (`email`(20)),
    KEY            `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员表';

DROP TABLE IF EXISTS `cms_member_role`;
CREATE TABLE `cms_member_role`
(
    `id`          smallint(6) unsigned NOT NULL AUTO_INCREMENT,
    `name`        varchar(20)  NOT NULL DEFAULT '' COMMENT '角色名称',
    `status`      tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用状态 0禁用 1启用',
    `remark`      varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
    `listorder`   int(3) NOT NULL DEFAULT '0' COMMENT '排序字段',
    PRIMARY KEY (`id`),
    KEY           `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色信息列表';

DROP TABLE IF EXISTS `cms_member_record_integration`;
CREATE TABLE `cms_member_record_integration`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id`   int(11) NOT NULL DEFAULT 0 COMMENT '上一条记录id，方便排除错误记录',
    `to`          varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流入者,一般为唯一ID',
    `to_type`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流入者者类型',
    `from`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流出者,一般为唯一ID',
    `from_type`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流出者类型',
    `target`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录来源者,一般为唯一ID',
    `target_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录来源者类型',
    `income`      decimal(10, 2)                                                NOT NULL DEFAULT 0.00 COMMENT '收入',
    `pay`         decimal(10, 2)                                                NOT NULL DEFAULT 0.00 COMMENT '支出',
    `balance`     decimal(10, 2)                                                NOT NULL DEFAULT 0.00 COMMENT '余额',
    `detail`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '记录详情',
    `status`      int(11) NOT NULL DEFAULT 0 COMMENT '状态，0位正常，1无效，2冻结',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间戳',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间戳',
    `remark`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
    `to_name`     varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '记录流入者名称',
    `from_name`   varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '记录流出者名称',
    `target_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '记录来源名称',
    `delete_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 65 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员积分表' ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `cms_member_record_trade`;
CREATE TABLE `cms_member_record_trade`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id`   int(11) NOT NULL DEFAULT 0 COMMENT '上一条记录id，方便排除错误记录',
    `to`          varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流入者,一般为唯一ID',
    `to_type`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流入者者类型',
    `from`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流出者,一般为唯一ID',
    `from_type`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录流出者类型',
    `target`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录来源者,一般为唯一ID',
    `target_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '记录来源者类型',
    `income`      decimal(10, 2)                                                NOT NULL DEFAULT 0.00 COMMENT '收入',
    `pay`         decimal(10, 2)                                                NOT NULL DEFAULT 0.00 COMMENT '支出',
    `balance`     decimal(10, 2)                                                NOT NULL DEFAULT 0.00 COMMENT '余额',
    `detail`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '记录详情',
    `status`      int(11) NOT NULL DEFAULT 0 COMMENT '状态，0位正常，1无效，2冻结',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间戳',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间戳',
    `remark`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
    `to_name`     varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '记录流入者名称',
    `from_name`   varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '记录流出者名称',
    `target_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '记录来源名称',
    `delete_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 63 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员余额表' ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `cms_member_grade`;
CREATE TABLE `cms_member_grade`
(
    `member_grade_id`   int(15) UNSIGNED NOT NULL AUTO_INCREMENT,
    `member_grade_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '等级名称',
    `meet_integration`  int(10) NULL DEFAULT NULL COMMENT '满足积分',
    `meet_trade`        int(10) NULL DEFAULT NULL COMMENT '满足余额',
    `member_sort`       int(10) UNSIGNED NULL DEFAULT NULL COMMENT '权重 （执行的条件按从小到大排序）',
    `discount`          decimal(5, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '折扣',
    `is_display`        int(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否开启 （0关闭  1开启）',
    `create_time`       int(11) NOT NULL DEFAULT 0 COMMENT '创建时间戳',
    `update_time`       int(11) NOT NULL DEFAULT 0 COMMENT '更新时间戳',
    `delete_time`       int(10) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
    PRIMARY KEY (`member_grade_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员等级表' ROW_FORMAT = Dynamic;


DROP TABLE IF EXISTS `cms_member_config`;
CREATE TABLE `cms_member_config`
(
    `member_config_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `create_time`      int(11) NOT NULL DEFAULT 0 COMMENT '创建时间戳',
    `update_time`      int(11) NOT NULL DEFAULT 0 COMMENT '更新时间戳',
    `delete_time`      int(10) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
    `varname`          varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `info`             varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `value`            text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    PRIMARY KEY (`member_config_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员配置表' ROW_FORMAT = Dynamic;

INSERT INTO `cms_member_config`
VALUES (1, 1623044937, 1623044937, 0, '会员审核开关', 'audit_switch', '0');
INSERT INTO `cms_member_config`
VALUES (2, 1623044937, 1623044937, 0, '拉黑审核开关', 'block_switch', '0');
INSERT INTO `cms_member_config`
VALUES (3, 1623044937, 1623044937, 0, '升级触发条件', 'grade_trigger', '2');


DROP TABLE IF EXISTS `cms_member_token`;
CREATE TABLE `cms_member_token`
(
    `access_token_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`         int(10) UNSIGNED NULL DEFAULT NULL,
    `access_token`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `expires_in`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `create_time`     int(11) NOT NULL DEFAULT 0 COMMENT '创建时间戳',
    `update_time`     int(11) NOT NULL DEFAULT 0 COMMENT '更新时间戳',
    `delete_time`     int(10) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
    PRIMARY KEY (`access_token_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员TOKEN表' ROW_FORMAT = Dynamic;



