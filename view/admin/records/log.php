<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-table
                :key="tableKey"
                :data="list"
                border
                fit
                highlight-current-row
                style="width: 100%;"
        >

            <el-table-column label="金额变动" align="center">
                <template slot-scope="scope">
                    <div v-if="scope.row.pay > 0 || scope.row.income > 0">
                        <span v-if="scope.row.pay > 0">- {{ scope.row.pay }}</span>
                        <span v-if="scope.row.income > 0">+ {{ scope.row.income }}</span>
                    </div>
                    <div v-else>
                        0
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="变动场景" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.detail }}</span>
                </template>
            </el-table-column>

            <el-table-column label="管理员备注" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.remark }}</span>
                </template>
            </el-table-column>

            <el-table-column v-if="listQuery.is_content == 1" label="金额来源" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.content }}</span>
                </template>
            </el-table-column>

            <el-table-column label="变动时间" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.create_time }}</span>
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
                form: {},
                tableKey: 0,
                list: [],
                total: 0,
                listQuery: {
                    page: 1,
                    limit: 20,
                    user_id: "{:input('user_id')}"
                },
                props: {
                    lazy: true
                },
                cate_list: [],
                multipleSelectionFlag: false,
                multiDeleteVisible: false,
                multipleSelection: ''
            },
            watch: {},
            filters: {},
            methods: {
                doSearch: function () {
                    var that = this;
                    that.listQuery.page = 1;
                    that.getList();
                },
                getList: function () {
                    var that = this;
                    var url = '{:input("log")}';
                    var data = that.listQuery;
                    data._action = 'list';
                    that.httpGet(url, data, function (res) {
                        if (res.status) {
                            that.list = res.data.data;
                            that.page = res.data.current_page;
                            that.total = parseInt(res.data.total);
                            that.page_count = res.data.last_page;
                        } else {
                            layer.msg(res.msg, {time: 1000});
                        }
                    });
                }
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>

