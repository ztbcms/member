<style>
    body .el-table th.gutter {
        display: table-cell !important;
    }
</style>

<div id="app" v-cloak>
    <el-card>
        <div slot="header" class="clearfix">
            <span>用户登录凭证</span>
        </div>
        <div class="wrapper">
            <div class="content-wrapper" style="margin-left:0;width:100%;">
                <section class="content">
                    <div class="box box-solid">
                        <div class="box-body">

                            <div class="filter-container">
                                <span style="margin-left: 10px;">用户token：</span>
                                <el-input size="small" v-model="listQuery.access_token"
                                          placeholder="用户token" style="width: 200px;"
                                          class="filter-item">
                                </el-input>

                                <span style="margin-left: 10px;">用户ID：</span>
                                <el-input size="small" v-model="listQuery.user_id"
                                          placeholder="用户ID" style="width: 200px;"
                                          class="filter-item">
                                </el-input>

                                <el-button @click="doSearch" size="small" type="primary" icon="el-icon-search">
                                    搜索
                                </el-button>

                                <el-button @click="getDetails(0)" size="small" type="success">
                                    新增
                                </el-button>
                            </div>

                            <el-table
                                    :data="lists"
                                    ref="multipleTable"
                                    fit
                                    highlight-current-row
                                    style="width: 100%;margin-top: 15px;">

                                <el-table-column fixed="left" prop="access_token" label="token">

                                </el-table-column>

                                <el-table-column prop="user_id" label="用户ID">

                                </el-table-column>

                                <el-table-column label="有效期">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.expires_in | parseTime  }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column min-width="100px" prop="create_time" label="创建时间">

                                </el-table-column>

                                <el-table-column fixed="right" label="操作" width="200">
                                    <template slot-scope="scope">
                                        <el-button style="color: red" @click="getDelete(scope.row.access_token_id)"
                                                   type="text" size="mini">删除
                                        </el-button>
                                    </template>
                                </el-table-column>
                            </el-table>

                            <div class="pagination-container" style="margin-top: 15px;">
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
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </el-card>
</div>


<script>
    $(document).ready(function () {
        new Vue({
            el: "#app",
            data: function () {
                return {
                    listQuery: {
                        page: 1,
                        limit: 20,
                        access_token: '',
                        user_id : ''
                    },
                    page: 1,
                    limit: 20,
                    lists: [],
                    page_count: 0,
                    total: 0
                };
            },
            filters: {
                parseTime: function (time, format) {
                    return Ztbcms.formatTime(time, format)
                },
                statusFilter: function (status) {
                    var statusMap = {
                        published: 'success',
                        draft: 'info',
                        deleted: 'danger'
                    };
                    return statusMap[status]
                }
            },
            mounted: function () {
                this.getList();
            },
            methods: {
                doSearch: function () {
                    var that = this;
                    that.listQuery.page = 1;
                    that.getList();
                },
                getList: function () {
                    var that = this;
                    var url = '{:api_url("member/admin.Token/index")}';
                    var data = that.listQuery;
                    data._action = 'list';
                    that.httpGet(url, data, function (res) {
                        if (res.status) {
                            that.lists = res.data.data;
                            that.total = res.data.total;
                            that.page = res.data.page;
                            that.page_count = res.data.per_page;
                        } else {
                            layer.msg(res.msg, {time: 1000});
                        }
                    });
                },
                getDelete: function (access_token_id) {
                    var that = this;
                    var url = '{:api_url("member/admin.Token/index")}';
                    layer.confirm('您确定需要删除该等级？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        that.httpPost(url, {
                            _action: 'delete',
                            access_token_id: access_token_id
                        }, function (res) {
                            if (res.status) {
                                layer.msg(res.msg, {time: 3000});
                                that.getList();
                            }
                        });
                    });
                },
                getDetails: function () {
                    var that = this;
                    var url = '';
                    url += '{:api_url("member/admin.Token/details")}';
                    layer.open({
                        type: 2,
                        content: url,
                        area: ['60%', '60%'],
                        end: function () {
                            that.getList();
                        }
                    });
                }
            }
        });
    });
</script>