<style>
    body .el-table th.gutter {
        display: table-cell !important;
    }
</style>

<div id="app" v-cloak>
    <el-card>
        <div slot="header" class="clearfix">
            <span>会员等级管理</span>
        </div>
        <div class="wrapper">
            <div class="content-wrapper" style="margin-left:0;width:100%;">
                <section class="content">
                    <div class="box box-solid">
                        <div class="box-body">

                            <?php if (\app\admin\service\AdminUserService::getInstance()->getInfo()['id'] == \app\admin\model\RoleModel::SUPER_ADMIN_ROLE_ID){ ?>
                                <div class="filter-container" style="margin-bottom: 20px;">
                                    <template>
                                        <el-alert
                                                title="超管可查看的内容"
                                                type="info"
                                                description="">
                                            <p>同步用户等级方法 ：  (new \app\member\model\MemberGradeModel())->sysMemberGrade(input('user_id')); </p>
                                        </el-alert>
                                    </template>
                                </div>
                            <?php } ?>

                            <div class="filter-container" style="margin-bottom: 20px;">
                                <template>
                                    <el-alert
                                            title="说明"
                                            type="success"
                                            description="">
                                        <p>满足消费积分和消费金额的情况下，权重越高优先成为该等级 </p>
                                    </el-alert>
                                </template>
                            </div>

                            <div class="filter-container">
                                <span style="margin-left: 10px;">等级名称：</span>
                                <el-input size="small" v-model="listQuery.member_grade_name"
                                          placeholder="等级名称" style="width: 200px;"
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
                                    border
                                    fit
                                    highlight-current-row
                                    style="width: 100%;margin-top: 15px;">

                                <el-table-column fixed="left" prop="member_grade_id" label="ID"></el-table-column>

                                <el-table-column prop="member_grade_name" label="等级名称"></el-table-column>

                                <el-table-column prop="meet_integration" label="消费积分"></el-table-column>

                                <el-table-column prop="meet_trade" label="消费金额"></el-table-column>

                                <el-table-column prop="member_sort" label="权重"></el-table-column>

                                <el-table-column min-width="100px" prop="create_time" label="创建时间"></el-table-column>

                                <el-table-column fixed="right" label="操作" width="200">
                                    <template slot-scope="scope">
                                        <el-button @click="getDetails(scope.row.member_grade_id)" size="small"
                                                   type="primary">详情
                                        </el-button>

                                        <el-button @click="getDelete(scope.row.member_grade_id)" size="small"
                                                   type="danger">删除
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
                        member_grade_name: ''
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
                    var url = '{:api_url("member/admin.Grade/index")}';
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
                getDetails: function (member_grade_id) {
                    var that = this;
                    var url = '';
                    url += '{:api_url("member/admin.Grade/details")}';
                    if (member_grade_id) url += '?member_grade_id=' + member_grade_id;
                    layer.open({
                        type: 2,
                        content: url,
                        area: ['100%', '100%'],
                        end: function () {
                            that.getList();
                        }
                    });
                },
                getDelete : function (member_grade_id) {
                    var that = this;
                    var url = '{:api_url("member/admin.Grade/index")}';
                    layer.confirm('您确定需要删除该等级？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        that.httpPost(url, {
                            _action: 'delete',
                            member_grade_id: member_grade_id
                        }, function (res) {
                            if (res.status) {
                                layer.msg(res.msg, {time: 3000});
                                that.getList();
                            }
                        });
                    });
                }
            }
        });
    });
</script>