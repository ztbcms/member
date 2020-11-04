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
            <el-table-column label="绑定日期" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.create_time}}</span>
                </template>
            </el-table-column>
            <el-table-column label="平台" align="center">
                <template slot-scope="{row}">
                    <span>{{ row.app_name }}</span>
                </template>
            </el-table-column>
            <el-table-column label="openid" min-width="120px" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.bind_open_id }}</span>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                    <el-button type="primary" size="mini" @click="unBind(row.bind_type)">
                        解绑
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
                user_id: "{:input('user_id')}",
                listQuery: {
                    page: 1,
                    limit: 20,
                },
            },
            watch: {},
            filters: {
                parseTime: function (time, format) {
                    return Ztbcms.formatTime(time, format)
                },
                statusFilter: function (status) {
                    var statusMap = {
                        published: 'success',
                        draft: 'info',
                        deleted: 'danger'
                    }
                    return statusMap[status]
                },
            },
            methods: {
                getList: function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/member/bind/getBindDetail')}",
                        data: {user_id: that.user_id},
                        method: 'get',
                        success: function (res) {
                            that.list = res.data
                        }
                    })
                },
                // 解绑
                unBind: function (bind_type) {
                    var that = this;
                    layer.confirm('确定要解绑？', {title:'提示'}, function(){
                        $.ajax({
                            url: "{:api_url('/member/bind/unBindUser')}",
                            data: {
                                user_id: that.user_id,
                                bind_type: bind_type,
                            },
                            method: 'post',
                            success: function (res) {
                                layer.msg(res.msg)
                                if (res.status) {
                                    that.getList()
                                }
                            }
                        })
                    });
                }
            },
            mounted: function () {
                this.getList();
            },

        })
    })
</script>
