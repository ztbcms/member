<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div slot="header" class="clearfix">
            <span>角色管理</span>
        </div>

        <?php if (\app\admin\service\AdminUserService::getInstance()->hasPermission('member', 'admin.Role', 'addRole')){ ?>
        <el-button class="filter-item" style="margin-left: 10px;margin-bottom: 15px;" size="small" type="primary"
                   @click="roleAdd('')">
            添加角色
        </el-button>
        <?php } ?>

        <el-table
                :data="Manager"
                highlight-current-row
                style="width: 100%;"
        >

            <el-table-column label="ID" align="center" width="100">
                <template slot-scope="scope">
                    <span>{{ scope.row.id }}</span>
                </template>
            </el-table-column>

            <el-table-column label="角色名称" align="">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                </template>
            </el-table-column>

            <el-table-column label="角色描述" align="">
                <template slot-scope="scope">
                    <span>{{ scope.row.remark }}</span>
                </template>
            </el-table-column>

            <el-table-column label="启用状态" align="center">
                <template slot-scope="scope">
                    <div v-if="scope.row.status == 1" class="el-icon-success"
                         style="color: #409EFF;font-size: 1.5rem"></div>
                    <div v-else class="el-icon-error" style="font-size: 1.5rem"></div>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center" width="530" class-name="small-padding fixed-width">
                <template slot-scope="scope">
                        <span>

                            <?php if (\app\admin\service\AdminUserService::getInstance()->hasPermission('member', 'admin.Role', 'editRole')){ ?>
                            <el-button type="text" size="mini" @click="roleEdit(scope.row.id)">
                                修改
                            </el-button>
                            <?php } ?>

                            <?php if (\app\admin\service\AdminUserService::getInstance()->hasPermission('member', 'admin.Role', 'deleteRole')){ ?>
                            <el-button type="text" size="mini" @click="handleDelete(scope.row.id)" style="color:#F56C6C">
                                删除
                            </el-button>
                            <?php } ?>
                        </span>
                </template>

            </el-table-column>
        </el-table>

        <div class="pagination-container">
            <el-pagination
                    background
                    layout="prev, pager, next, jumper"
                    :total="total"
                    v-show="total>0"
                    :current-page.sync="listQuery.page"
                    :page-size.sync="listQuery.limit"
                    @current-change="getList"
            >
            </el-pagination>
        </div>

    </el-card>
</div>

<style>
    .itembtn {
        margin-top: 10px;

    }

    .el-button + .el-button {
        margin-left: 1px;
    }

    .filter-container {
        padding-bottom: 10px;
    }

    .pagination-container {
        padding: 32px 16px;
    }
</style>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                tableKey: 0,
                list: [],
                total: 0,
                input_date: ['', ''],
                listQuery: {
                    page: 1,
                    tab: '',
                    limit: 20,
                    start_time: '',
                    end_time: '',
                    user_name: '',
                    title: ''
                },
                Manager: []
            },
            watch: {},
            filters: {},
            methods: {
                getList: function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/member/admin.Role/index')}?_action=getList",
                        type: "get",
                        dataType: "json",
                        success: function (res) {
                            if (res.status) {
                                that.Manager = res.data
                            }
                        }
                    })
                },
                search: function () {
                    this.getList();
                },
                roleAdd: function () {
                    var url = '{:api_url("/member/admin.Role/addRole")}';
                    this.__openWindow(url, '添加角色');
                },
                roleEdit: function (id) {
                    var url = '{:api_url("/member/admin.Role/editRole")}';
                    url += '?id=' + id;
                    this.__openWindow(url, '编辑角色');
                },
                __openWindow:function(url, title) {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: title,
                        content: url,
                        area: ['95%', '95%'],
                        end: function () {
                            that.getList();
                        }
                    })
                },
                handleDelete: function (id) {
                    var that = this;
                    layer.confirm('是否确定删除该内容吗？', {
                        btn: ['确认', '取消'] //按钮
                    }, function () {
                        that.toDelete(id);
                        layer.closeAll();
                    }, function () {
                        layer.closeAll();
                    });
                },
                toDelete: function (id) {
                    var that = this;
                    that.httpPost("{:api_url('/member/admin.Role/deleteRole')}",  {id: id}, function(res){
                        if (res.status) {
                            that.$message.success(res.msg);
                            that.getList();
                        } else {
                            that.$message.error(res.msg);
                        }
                    } )
                },
            },
            mounted: function () {
                this.getList()
            }
        })
    })
</script>