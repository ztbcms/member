<div id="app" v-cloak>
    <el-card>
        <el-form :inline="true" :model="searchForm">

            <el-form-item label="用户ID">
                <el-input v-model="searchForm.user_id" placeholder=""></el-input>
            </el-form-item>

            <el-form-item label="用户名">
                <el-input v-model="searchForm.username" placeholder=""></el-input>
            </el-form-item>

            <el-form-item label="电话">
                <el-input v-model="searchForm.phone" placeholder=""></el-input>
            </el-form-item>

            <el-form-item label="邮箱">
                <el-input v-model="searchForm.email" placeholder=""></el-input>
            </el-form-item>

            <el-form-item label="角色">
                <el-select v-model="searchForm.role_id">
                    </el-option>
                    <el-option
                            v-for="item in roleList"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

        </el-form>
        <div>
            <el-button type="primary" @click="search" size="mini">查询</el-button>
            <el-button @click="add()" type="primary" size="mini">添加会员</el-button>
        </div>
        <el-tabs v-model="searchForm.tab" >
            <el-tab-pane label="全部" name="0"></el-tab-pane>
            <el-tab-pane label="待审核"  name="1"></el-tab-pane>
            <el-tab-pane label="审核不通过" name="2"></el-tab-pane>
            <el-tab-pane label="已拉黑" name="3"></el-tab-pane>
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
                    align="center"
                    prop="role_name"
                    label="角色"
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
                    align="center"
                    min-width="60"
                    label="审核">
                <template slot-scope="scope">
                    <span v-if="scope.row.audit_status == 0">待审核</span>
                    <span v-else-if="scope.row.audit_status == 1" style="color:#67C23A">通过</span>
                    <span v-else style="color:#F56C6C">不通过</span>
                </template>
            </el-table-column>

            <el-table-column
                    min-width="100"
                    prop="is_block"
                    align="center"
                    label="拉黑">
                <template slot-scope="scope">
                    <span v-if="scope.row.is_block ==0" style="color:#67C23A">正常</span>
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

                    <el-button v-if="scope.row.is_block == 1" @click="blockMember(scope.row.user_id, 0)" type="text" size="mini">取消拉黑</el-button>
                    <el-button v-if="scope.row.is_block == 0" @click="blockMember(scope.row.user_id, 1)" type="text" size="mini" style="color:#F56C6C">拉黑</el-button>

                    <el-button v-if="scope.row.audit_status == 0" @click="auditMember(scope.row.user_id, 1)" type="text" size="mini" >审核通过</el-button>
                    <el-button v-if="scope.row.audit_status == 0" @click="auditMember(scope.row.user_id, 2)" type="text" size="mini" style="color:#F56C6C">审核不通过</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div style="margin-top: 6px;">
            <el-button type="primary" @click="auditMember(0, 1)" size="mini">审核通过</el-button>
            <el-button type="danger" @click="auditMember(0, 2)" size="mini">审核不通过</el-button>
            <el-button type="primary" @click="blockMember(0,0)" size="mini">取消拉黑</el-button>
            <el-button type="danger" @click="blockMember(0,1)" size="mini">拉黑</el-button>
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
                    user_id: "",
                    username: "",
                    phone: "",
                    email: "",
                    tab: "0",
                },
                defaultImage: '/statics/images/member/nophoto.gif',
                multipleSelection: [],
                selectUserIds: [],
                lists: [],
                totalCount: 0,
                pageSize: 15,
                pageCount: 0,
                currentPage: 1,
                roleList: []
            },
            watch: {
               "searchForm.tab": function (){
                   this.getList()
                }
            },
            computed: {
                request_url: function(){
                    return "{:api_Url('/member/admin.member/index')}"
                }
            },
            mounted: function () {
                this.getRoleList()
                this.getList()
            },
            methods: {
                // 添加页面
                add: function () {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加会员',
                        content: "{:api_url('/member/admin.member/addMember')}",
                        area: ['60%', '80%'],
                        end: function () {
                            that.getList()
                        }
                    })
                },
                // 编辑页面
                editUser: function (user_id) {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '编辑会员',
                        content: "{:api_url('/member/admin.member/editMember')}" + '?user_id=' + user_id,
                        area: ['60%', '80%'],
                        end: function () {
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
                currentPageChange: function (e) {
                    this.currentPage = e;
                    this.getList();
                },
                // 搜索
                search: function () {
                    this.currentPage = 1;
                    this.getList();
                },
                // 获取列表
                getList: function () {
                    var that = this
                    var data = this.searchForm
                    data['_action'] = 'getList'
                    this.httpGet(this.request_url, data, function(res){
                        var data = res.data
                        that.lists = data.items
                        that.totalCount = data.total_items
                        that.pageSize = data.limit
                        that.pageCount = data.page
                    })
                },
                // 拉黑/恢复
                blockMember: function (userId, is_block) {
                    var that = this
                    var data = {}
                    // 批量
                    if (userId === 0) {
                        if (this.selectUserIds.length <= 0) {
                            layer.msg('请选择')
                            return
                        }
                        data = {'_action': 'batchBlockMember', user_ids: this.selectUserIds, is_block: is_block}
                    } else {
                        data = {'_action': 'blockMember', user_id: userId, is_block: is_block}
                    }
                    this.httpPost(this.request_url, data, function (res) {
                        if (res.status) {
                            that.getList()
                        }
                        layer.msg(res.msg)
                    })
                },
                // 审核
                auditMember: function (userId, audit_status) {
                    var that = this
                    var data = {}
                    // 批量
                    if (userId === 0) {
                        if (this.selectUserIds.length === 0) {
                            layer.msg('请选择')
                            return
                        }
                        data = {'_action': 'batchAuditMember', user_ids: this.selectUserIds, audit_status: audit_status}
                    } else {
                        data = {'_action': 'auditMember', user_id: userId, audit_status: audit_status}
                    }
                    this.httpPost(this.request_url, data, function (res) {
                        if (res.status) {
                            that.getList()
                        }
                        layer.msg(res.msg)
                    })
                },
                //获取所有角色
                getRoleList: function () {
                    var that = this
                    this.httpGet(this.request_url, {_action: 'getRoleList'}, function(res){
                        if (res.status) {
                            that.roleList = res.data
                        }
                    })
                }
            }
        });
    })
</script>
