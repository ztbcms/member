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
                "route"  => "member/admin.Dashboard/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "概览",
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
                        "route"  => "member/admin.Member/index",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "会员列表",
                        "remark" => "",
                        "child"  => [
                            [
                                "route"  => "member/user/getUserList",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "获取会员列表",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/bind/bindDetail",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "获取会员绑定详情",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/add",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "添加会员页面",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/addUser",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "添加会员",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/edit",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "编辑会员页面",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/editUser",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "编辑会员",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/auditUser",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "审核会员",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/cancelAuditUser",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "取消审核会员",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/blockUser",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "拉黑会员",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/user/delUser",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "删除会员",
                                "remark" => "",
                            ]
                        ]
                    ],

                    [
                        "route"  => "member/connect/lists",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "登录授权管理",
                        "remark" => "",
                        "child"  => []
                    ],
                ]
            ],
            [
                "route"  => "member/tag/lists",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员标签",
                "remark" => "",
                "child"  => [
                    [
                        "route"  => "member/tag/getList",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "获取会员标签列表",
                        "remark" => "",
                    ],
                    [
                        "route"  => "member/tag/addEdit",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "添加编辑会员标签",
                        "remark" => "",
                    ],
                    [
                        "route"  => "member/tag/getDetail",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "获取会员标签详情",
                        "remark" => "",
                    ],
                    [
                        "route"  => "member/tag/delTag",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "删除会员标签",
                        "remark" => "",
                    ],
                    [
                        "route"  => "member/tag/updateField",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "更新会员标签字段",
                        "remark" => "",
                    ],
                ]
            ],
            [
                "route"  => "member/setting/index",
                "type"   => 0,
                "status" => 0,
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
                        "route"  => "member/open/index",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "第三方平台设置",
                        "remark" => "",
                        "child"  => [
                            [
                                "route"  => "member/open/addEditApp",
                                "type"   => 1,
                                "status" => 1,
                                "name"   => "添加编辑第三方平台",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/open/delApp",
                                "type"   => 1,
                                "status" => 1,
                                "name"   => "删除第三方平台",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/open/getDetail",
                                "type"   => 1,
                                "status" => 1,
                                "name"   => "获取第三方平台详情",
                                "remark" => "",
                            ],
                        ]
                    ],
                    [
                        "route"  => "member/model/lists",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "模型管理",
                        "remark" => "",
                        "child"  => [
                            [
                                "route"  => "member/model/getList",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "获取模型列表",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/model/getList",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "获取模型列表",
                                "remark" => "",
                            ],
                            [
                                "route"  => "member/model/detail",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "添加编辑模型",
                                "remark" => "",
                                "child"  => [
                                    [
                                        "route"  => "member/model/addEditModel",
                                        "type"   => 1,
                                        "status" => 0,
                                        "name"   => "添加编辑模型操作权限",
                                        "remark" => "",
                                    ],
                                ]
                            ],
                            [
                                "route"  => "member/model/delModel",
                                "type"   => 1,
                                "status" => 0,
                                "name"   => "删除模型",
                                "remark" => "",
                            ]
                        ]
                    ],
                ]
            ],
        ],
    ]
];
