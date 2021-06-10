<?php

return [
    [
        "parentid" => 0,
        "route"    => "member/index/index",
        "type"     => 0,
        "status"   => 1,
        "name"     => "会员",
        "remark"   => "",
        "icon"     => "icon-yonghuguanli",
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
                "route"  => "member/admin.Member/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员管理",
                "remark" => "",
                "child"  => []
            ],
            [
                "route"  => "member/admin.Role/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "角色管理",
                "remark" => "",
            ],
            [
                "route"  => "member/admin.Grade/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "等级管理",
                "remark" => "",
            ],
            [
                "route"  => "member/admin.Config/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员设置",
                "remark" => "",
            ]
            ,
            [
                "route"  => "member/admin.Token/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "登录凭证",
                "remark" => "",
            ]
        ]
    ]
];
