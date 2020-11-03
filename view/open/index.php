<div>
    <div id="app" v-cloak>
        <el-card>
            <div slot="header" class="clearfix">
                <span>第三方平台应用列表</span>
            </div>
            <div>
                <el-button @click="addEvent" type="primary" size="mini">添加应用</el-button>
            </div>
            <div style="margin-top: 10px">
                <el-table
                    :data="applications"
                    border
                    style="width: 100%">
                    <el-table-column
                        prop="app_name"
                        align="center"
                        label="应用名称"
                        min-width="100">
                    </el-table-column>

                    <el-table-column
                        label="APP_KEY"
                        prop="app_key"
                        align="center"
                        min-width="240">
                    </el-table-column>

                    <el-table-column
                        label="APP_SECRET"
                        prop="app_secret"
                        align="center"
                        min-width="240">
                    </el-table-column>
                    <el-table-column
                        align="center"
                        label="创建时间"
                        prop="create_time"
                        min-width="180">
                    </el-table-column>
                    <el-table-column
                        label="操作"
                        align="center"
                        min-width="180">
                        <template slot-scope="scope">
                            <el-button @click="editEvent(scope.row)" type="primary" size="mini">编辑</el-button>
                            <el-button @click="deleteEvent(scope.row)" type="danger" size="mini" >删除
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>

                <div class="pagination-container">
                    <el-pagination
                        background
                        layout="prev, pager, next, jumper"
                        :total="total"
                        v-show="total>0"
                        :current-page.sync="form.page"
                        :page-size.sync="form.limit"
                        @current-change="getList"
                    >
                    </el-pagination>
                </div>
            </div>
        </el-card>

    </div>
    <style>
        .pagination-container {
            text-align: center;
            padding: 32px 16px;
        }
    </style>
    <script>
        $(document).ready(function () {
            new Vue({
                el: "#app",
                data: {
                    applications: [],
                    form: {
                        page: 1,
                        limit: 10,
                    },
                    total: 0
                },
                mounted() {
                    this.getList()
                },
                methods: {
                    deleteEvent: function (item) {
                        var _this = this;
                        this.$confirm('是否确认删除"' + item.app_type + '" ？').then(() => {
                            _this.doDeleteItem(item)
                        }).catch()
                    },
                    doDeleteItem: function (item) {
                        var _this = this;
                        //确认删除
                        $.ajax({
                            url: "{:api_url('/member/open/delApp')}",
                            data: {id: item.id},
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    layer.msg('删除成功');
                                    _this.getList()
                                } else {
                                    layer.msg(res.msg)
                                }
                            }
                        })
                    },

                    getList: function () {
                        var _this = this;
                        $.ajax({
                            url: "{:api_url('/member/open/getList')}",
                            data: this.form,
                            dataType: 'json',
                            type: 'get',
                            success: function (res) {
                                if (res.status) {
                                    _this.applications = res.data.data;
                                    _this.total = res.data.total;
                                    _this.form.page = res.data.current_page;
                                    _this.form.limit = res.data.per_page
                                }
                            }
                        })
                    },
                    addEvent: function () {
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '添加',
                            content: "{:api_url('/member/open/detail')}",
                            area: ['50%', '45%'],
                            end: function () {  //回调函数
                                that.getList()
                            }
                        })
                    },
                    editEvent: function (editItem) {
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '编辑',
                            content: "{:api_url('/member/open/detail')}?id=" + editItem.id,
                            area: ['50%', '45%'],
                            end: function () {  //回调函数
                                that.getList()
                            }
                        })
                    },
                }
            })
        })
    </script>
</div>
