<?php

return [
    [
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => 0,
        //地址，[模块/]控制器/方法
        "route"    => "member/index/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type"     => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status"   => 1,
        //名称
        "name"     => "会员",
        //备注
        "remark"   => "",
        //子菜单列表
        "child"    => [
            [
                "route"  => "member/admin/dashboard",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员概览",
                "remark" => "",
                "child"  => []
            ],
            [
                "route"  => "member/user/lists",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员管理",
                "remark" => "",
                "child"  => [
                    [
                        "route"  => "member/user/lists",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "会员列表",
                        "remark" => "",
                        "child"  => []
                    ],
                    [
                        "route"  => "member/user/unAuditLists",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "审核会员",
                        "remark" => "",
                        "child"  => []
                    ],
                    [
                        "route"  => "member/user/auth",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "登录授权管理",
                        "remark" => "",
                        "child"  => []
                    ],
                ]
            ],
            [
                "route"  => "member/group/lists",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员组列表",
                "remark" => "",
                "child"  => [
                ]
            ],
            [
                "route"  => "member/tag/lists",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员标签",
                "remark" => "",
                "child"  => []
            ],
            [
                "route"  => "member/setting/index",
                "type"   => 0,
                "status" => 1,
                "name"   => "设置",
                "remark" => "",
                "child"  => [
                    [
                        "route"  => "member/setting/index",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "会员设置",
                        "remark" => "",
                        "child"  => []
                    ],
                    [
                        "route"  => "member/model/index",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "模型管理",
                        "remark" => "",
                        "child"  => []
                    ],
                ]
            ],
        ],
    ]
];
