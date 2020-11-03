<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div>
            <h3>温馨提示</h3>
            <p>1、请谨慎删除模型，当模型里存在会员时请使用“移动”功能将该模型里的会员移动到其他会员模型中。
                <br>
                2、移动模型会员，将会把原有模型里的会员信息删除，将不能修复。</p>
        </div>
        <div>
            <el-button size="mini" type="primary" @click="detailModel(0)">添加模型</el-button>
        </div>
        <el-table size=""
                  :key="tableKey"
                  :data="list"
                  fit
                  highlight-current-row
                  style="width: 100%;"
        >
            <el-table-column label="ID" width="" align="center">
                <template slot-scope="{row}">
                    <span>{{ row.modelid }}</span>
                </template>
            </el-table-column>

            <el-table-column label="模型名称" width="" align="center">
                <template slot-scope="{row}">
                    <span>{{ row.name }}</span>
                </template>
            </el-table-column>

            <el-table-column label="模型描述" width="" align="center">
                <template slot-scope="{row}">
                    {{ row.description }}
                </template>
            </el-table-column>

            <el-table-column label="数据表名" align="center">
                <template slot-scope="{row}">
                    {{ row.tablename }}
                </template>
            </el-table-column>

            <el-table-column label="状态" width="" align="center">
                <template slot-scope="{row}">
                     <span class="el-icon-success" style="color: green;font-size: 24px;"
                           v-if="row.disabled == '0'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.disabled == '1'"></span>
                </template>
            </el-table-column>

            <el-table-column label="操作" width="300px" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                    <el-button type="primary" size="mini" @click="detailModel(row.modelid)">
                        编辑
                    </el-button>
                    <el-button type="danger" size="mini" @click="deleteItem(row.modelid)">
                        删除
                    </el-button>
                </template>
            </el-table-column>

        </el-table>
        <div class="pagination-container">
            <el-pagination
                background
                layout="prev, pager, next, jumper"
                :total="total"
                v-show="total > 0"
                :page-count="page_count"
                :current-page.sync="listQuery.page"
                :page-size.sync="listQuery.limit"
                @current-change="getList"
            >
            </el-pagination>
        </div>


    </el-card>
</div>

<style>
    .filter-container {
        padding-bottom: 10px;
    }

    .pagination-container {
        padding: 32px 16px;
    }

    .update-sort {
        font-size: 16px;
        color: #409EFF;
        cursor: pointer;
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
                page_count: 0,
                listQuery: {
                    page: 1,
                    limit: 15,
                }
            },
            watch: {},
            filters: {
                parseTime: function (time, format) {
                    return Ztbcms.formatTime(time, format)
                },
            },
            methods: {
                doSearch: function () {
                    var that = this;
                    that.listQuery.page = 1;
                    that.getList();
                },
                getList: function () {
                    var that = this;
                    var url = '{:api_url("/member/model/getList")}';
                    var data = that.listQuery;
                    that.httpGet(url, data, function (res) {
                        if (res.status) {
                            that.list = res.data.data;
                            that.total = res.data.total;
                            that.page_count = res.data.per_page;
                        }
                    });
                },
                // 添加模型
                detailModel:function (id) {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '详情',
                        content: "{:api_url('/member/model/detail')}?model_id=" + id,
                        area: ['50%', '90%'],
                        end: function () {  //回调函数
                            that.getList()
                        }
                    })
                },
                // 删除
                deleteItem: function (id) {
                    var that = this;
                    var url = '{:api_url("/member/model/delModel")}';
                    layer.confirm('您确定需要删除？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        that.httpPost(url, {model_id: id}, function (res) {
                            layer.msg(res.msg);
                            if (res.status) {
                                that.getList();
                            }
                        });
                    });
                },
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>
