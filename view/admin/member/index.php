<div id="app" v-cloak>
    <el-card>
        <el-form :inline="true" :model="searchForm">
            <el-form-item label="注册日期">
                <el-date-picker
                        v-model="searchForm.datetime"
                        type="datetimerange"
                        range-separator="至"
                        value-format="yyyy-MM-dd HH:mm:ss"
                        start-placeholder="注册开始日期"
                        end-placeholder="注册结束日期">
                </el-date-picker>
            </el-form-item>

            <el-form-item label="">
                <el-input v-model="searchForm.search" placeholder="用户名、用户id"></el-input>
            </el-form-item>

            <el-form-item label="">
                <el-input v-model="searchForm.tag_name" placeholder="标签"></el-input>
            </el-form-item>

        </el-form>
        <div>
            <el-button type="primary" @click="search" size="mini">查询</el-button>
            <el-button @click="add()" type="primary" size="mini">添加会员</el-button>
        </div>
        <el-tabs @tab-click="handleClickTabs">
            <el-tab-pane label="全部"></el-tab-pane>
            <el-tab-pane label="待审核"></el-tab-pane>
            <el-tab-pane label="审核不通过"></el-tab-pane>
            <el-tab-pane label="已拉黑"></el-tab-pane>
        </el-tabs>
        <el-table
            :data="lists"
            style="width: 100%"
            @selection-change="handleSelectionChange"
        >
            <el-table-column
                type="selection"
                width="55"
                label="全选">
            </el-table-column>


            <el-table-column
                align="center"
                prop="user_id"
                label="用户ID"
                min-width="60">
            </el-table-column>

            <el-table-column
                min-width="80"
                align="center"
                label="头像"
            >
                <template slot-scope="scope">
                    <el-image :src="scope.row.avatar" :preview-src-list="[scope.row.avatar]"
                              v-if="scope.row.avatar"></el-image>
                    <el-image :src="defaultImage" :preview-src-list="[defaultImage]" v-else></el-image>
                </template>
            </el-table-column>

            <el-table-column
                min-width="80"
                prop="username"
                align="center"
                label="用户名"
            >
            </el-table-column>

            <el-table-column
                min-width="100"
                prop="tags_name"
                align="center"
                label="标签">
            </el-table-column>

            <el-table-column
                min-width="80"
                prop="email"
                align="center"
                label="邮箱"
            >
            </el-table-column>

            <el-table-column
                    min-width="80"
                    prop="phone"
                    align="center"
                    label="电话"
            >
            </el-table-column>

            <el-table-column
                min-width="80"
                align="center"
                label="模型名称">
                <template slot-scope="scope">
                    <div>-</div>
                </template>
            </el-table-column>

            <el-table-column
                min-width="100"
                prop="point"
                align="center"
                label="积分点数">
            </el-table-column>

            <el-table-column
                    align="center"
                    min-width="60"
                    label="审核">
                <template slot-scope="scope">
                    <span v-if="scope.row.check_status == 0">待审核</span>
                    <span v-else-if="scope.row.check_status == 1" style="color:#67C23A">通过</span>
                    <span v-else style="color:#F56C6C">不通过</span>
                </template>
            </el-table-column>

            <el-table-column
                    min-width="100"
                    prop="is_block"
                    align="center"
                    label="拉黑">
                <template slot-scope="scope">
                    <span v-if="scope.row.is_block ==0">正常</span>
                    <span v-else style="color:#F56C6C">已拉黑</span>
                </template>
            </el-table-column>

            <el-table-column
                min-width="230"
                align="center"
                fixed="right"
                label="操作">
                <template slot-scope="scope">
                    <el-button @click="openDetail(scope.row.user_id)" type="text" size="mini">查看</el-button>
                    <el-button @click="editUser(scope.row.user_id)" type="text" size="mini">编辑</el-button>

                    <el-button v-if="scope.row.is_block == 1" @click="blockUser(scope.row.user_id,0,'')" type="text" size="mini"  style="color:#F56C6C">取消拉黑</el-button>
                    <el-button v-if="scope.row.is_block == 0" @click="blockUser(scope.row.user_id,1,'')" type="text" size="mini" style="color:#F56C6C">拉黑</el-button>

                    <el-button v-if="scope.row.check_status == 0" @click="batchUpdateNoAudit(scope.row.user_id,'')" type="text" size="mini"  style="color:#F56C6C">审核通过</el-button>
                    <el-button v-if="scope.row.check_status == 0" @click="batchUpdateAudit(scope.row.user_id,'')" type="text" size="mini" style="color:#F56C6C">审核不通过</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div style="margin-top: 6px;">
            <el-button type="primary" @click="batchUpdateAudit('',true)" size="mini">批量审核</el-button>
            <el-button type="primary" @click="batchUpdateNoAudit('',true)" size="mini">取消审核</el-button>
            <el-button type="danger" @click="blockUser('',1,true)" size="mini">拉黑</el-button>
            <el-button type="danger" @click="blockUser('',0,true)" size="mini">取消拉黑</el-button>
        </div>

        <div style="text-align: center;margin-top: 20px">
            <el-pagination
                    background
                    @current-change="currentPageChange"
                    layout="prev, pager, next"
                    :current-page="currentPage"
                    :page-count="totalCount"
                    :page-size="pageSize"
                    :total="totalCount">
            </el-pagination>
        </div>
    </el-card>
</div>
<script>
    $(function () {
        new Vue({
            el: "#app",
            data: {
                searchForm: {
                    datetime: "",
                    search: "",
                    is_block: "",
                    checked: "",
                    tag_name: "",
                },
                statusOptions: [
                    {
                        label: '是',
                        value: '1'
                    },
                    {
                        label: '否',
                        value: '0'
                    },
                ],
                defaultImage: '/statics/images/member/nophoto.gif',
                multipleSelection: [],
                selectUserIds: [],
                lists: [],
                totalCount: 0,
                pageSize: 10,
                pageCount: 0,
                currentPage: 1
            },
            mounted: function () {
                this.getList();
            },
            methods: {
                // 绑定详情
                openDetail:function(user_id){
                    layer.open({
                        type: 2,
                        title: '绑定详情',
                        content: "{:api_url('/member/bind/bindDetail')}" + '?user_id=' + user_id,
                        area: ['80%', '80%'],
                        end: function () {  //回调函数
                        }
                    })
                },
                // 添加页面
                add: function () {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加',
                        content: "{:api_url('/member/user/add')}",
                        area: ['80%', '90%'],
                        end: function () {  //回调函数
                            that.getList()
                        }
                    })
                },
                // 编辑页面
                editUser: function (user_id) {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '编辑',
                        content: "{:api_url('/member/user/edit')}" + '?user_id=' + user_id,
                        area: ['80%', '90%'],
                        end: function () {  //回调函数
                            that.getList()
                        }
                    })
                },
                // 全选
                handleSelectionChange: function (val) {
                    this.multipleSelection = val;
                    var selectUserIds = [];
                    this.multipleSelection.forEach(function (val) {
                        selectUserIds.push(val.user_id);
                    })
                    this.selectUserIds = selectUserIds;
                },
                // 搜索
                search: function () {
                    this.currentPage = 1;
                    this.getList();
                },
                currentPageChange: function (e) {
                    this.currentPage = e;
                    this.getList();
                },
                getList: function () {
                    var _this = this;
                    $.ajax({
                        url: "{:api_url('/member/admin.Member/getUserList')}",
                        data: _this.searchForm,
                        dataType: 'json',
                        type: 'get',
                        success: function (res) {
                            var data = res.data;
                            _this.lists = data.data;
                            _this.totalCount = data.total;
                            _this.pageSize = data.per_page;
                            _this.pageCount = data.last_page;
                            _this.currentPage = data.current_page;
                        }
                    })
                },
                // 拉黑/恢复
                blockUser: function (userId, status, batch) {
                    var _this = this;
                    var userIds = [];
                    // 批量
                    if (batch == true) {
                        userIds = this.selectUserIds;
                        if (userIds.length == 0) {
                            layer.msg('请选择')
                            return false;
                        }
                    } else {
                        // 单次
                        userIds.push(userId)
                    }
                    $.ajax({
                        url: "{:api_url('/member/user/blockUser')}",
                        data: {
                            user_id: userIds,
                            is_block: status,
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (res) {
                            if (res.status) {
                                _this.getList()
                            }
                            layer.msg(res.msg)
                        }
                    })
                },
                // 审核
                batchUpdateAudit: function (userId, batch) {
                    var _this = this;
                    var userIds = [];
                    // 批量
                    if (batch == true) {
                        userIds = this.selectUserIds;
                        if (userIds.length == 0) {
                            layer.msg('请选择')
                            return false;
                        }
                    } else {
                        // 单次
                        userIds.push(userId)
                    }
                    layer.confirm('确定要审核通过吗？', {}, function () {
                        $.ajax({
                            url: "{:api_url('/member/user/auditUser')}",
                            data: {
                                user_id: userIds,
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    _this.getList()
                                }
                                layer.msg(res.msg)
                            }
                        })
                    }, function () {
                        // 取消
                    });
                },
                // 取消审核
                batchUpdateNoAudit: function (userId, batch) {
                    var _this = this;
                    var userIds = [];
                    // 批量
                    if (batch == true) {
                        userIds = this.selectUserIds;
                        if (userIds.length == 0) {
                            layer.msg('请选择')
                            return false;
                        }
                    } else {
                        // 单次
                        userIds.push(userId)
                    }
                    layer.confirm('确定要取消审核吗？', {}, function () {
                        $.ajax({
                            url: "{:api_url('/member/user/cancelAuditUser')}",
                            data: {
                                user_id: userIds,
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    _this.getList()
                                }
                                layer.msg(res.msg)
                            }
                        })
                    }, function () {
                        // 取消
                    });
                },
                // 删除用户
                delUser: function (userId, batch) {
                    var _this = this;
                    var userIds = [];
                    // 批量
                    if (batch == true) {
                        userIds = this.selectUserIds;
                        if (userIds.length == 0) {
                            layer.msg('请选择')
                            return false;
                        }
                    } else {
                        // 单次
                        userIds.push(userId)
                    }
                    layer.confirm('确定要删除吗？', {}, function () {
                        $.ajax({
                            url: "{:api_url('/member/user/delUser')}",
                            data: {
                                user_id: userIds,
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    _this.getList()
                                }
                                layer.msg(res.msg)
                            }
                        })
                    })
                }
            }
        });
    })
</script>
