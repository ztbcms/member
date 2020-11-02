<div id="app" v-cloak>
    <el-card>
        <div>
            <el-form :inline="true" :model="searchForm" class="demo-form-inline">
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
                <el-form-item label="审核状态">
                    <el-select v-model="searchForm.checked">
                        <el-option
                            v-for="item in statusOptions"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="拉黑状态">
                    <el-select v-model="searchForm.is_block">
                        <el-option
                            v-for="item in statusOptions"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="">
                    <el-input v-model="searchForm.search" placeholder="用户名、用户id"></el-input>
                </el-form-item>
                <el-form-item label="">
                    <el-input v-model="searchForm.tag_name" placeholder="用户标签名"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="search">查询</el-button>
                    <el-button @click="add()" type="primary">添加会员</el-button>
                </el-form-item>
            </el-form>
        </div>
        <el-table
            :data="lists"
            border
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
                min-width="60"
                label="审核状态">
                <template slot-scope="scope">
                    <el-link :underline="false" @click="batchUpdateNoAudit(scope.row.user_id,'')"
                             class="el-icon-success" style="color: green;font-size: 24px;"
                             v-if="scope.row.checked == '1'"></el-link>
                    <el-link :underline="false" v-else @click="batchUpdateAudit(scope.row.user_id,'')"><span
                            class="el-icon-error" style="color: red;font-size: 24px;"></span></el-link>
                </template>
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
                    <el-image :src="scope.row.userpic" :preview-src-list="[scope.row.userpic]"
                              v-if="scope.row.userpic"></el-image>
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
                align="center"
                label="模型名称">
                <template slot-scope="scope">
                    <div>TODO ！！！</div>
                </template>
            </el-table-column>

            <el-table-column
                min-width="100"
                prop="regip"
                align="center"
                label="注册ip">
            </el-table-column>

            <el-table-column
                min-width="100"
                align="center"
                label="最后登录">
                <template slot-scope="scope">
                    <span v-if="scope.row.lastlogin">{{scope.row.lastlogin}}</span>
                    <span v-else>用户暂未登录</span>
                </template>
            </el-table-column>

            <el-table-column
                min-width="100"
                prop="amount"
                align="center"
                label="金钱总数">
            </el-table-column>

            <el-table-column
                min-width="100"
                prop="point"
                align="center"
                label="积分点数">
            </el-table-column>

            <el-table-column
                min-width="330"
                align="center"
                label="操作">
                <template slot-scope="scope">
                    <el-button @click="openDetail(scope.row.user_id)" type="success" size="mini">查看详情</el-button>
                    <el-button @click="editUser(scope.row.user_id)" type="primary" size="mini">修改</el-button>

                    <template v-if="scope.row.is_block == 1">
                        <el-button @click="blockUser(scope.row.user_id,0,'')" type="success" size="mini">
                            恢复
                        </el-button>
                        <el-button @click="delUser(scope.row.user_id,'')" type="danger" size="mini">
                            删除
                        </el-button>
                    </template>
                    <el-button @click="blockUser(scope.row.user_id,1,'')" type="danger" v-if="scope.row.is_block == 0"
                               size="mini">拉黑
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
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
        <div>
            <el-button type="primary" @click="batchUpdateAudit('',true)" size="mini">批量审核</el-button>
            <el-button type="primary" @click="batchUpdateNoAudit('',true)" size="mini">取消审核</el-button>
            <el-button type="danger" @click="blockUser('',1,true)" size="mini">拉黑</el-button>
            <el-button type="primary" @click="blockUser('',0,true)" size="mini">取消拉黑</el-button>
            <el-button type="danger" @click="delUser('',true)" size="mini">删除</el-button>
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
                        value: 1
                    },
                    {
                        label: '否',
                        value: 0
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
                // 查看详情
                openDetail:function(user_id){
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加',
                        content: "{:api_url('/member/user/add')}" + '?user_id=' + user_id,
                        area: ['30%', '90%'],
                        end: function () {  //回调函数
                            that.getList()
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
                        area: ['30%', '90%'],
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
                        area: ['30%', '90%'],
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
                        url: "{:api_url('/member/user/getUserList')}",
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
