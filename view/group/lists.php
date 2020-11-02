<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div class="filter-container">
            <el-input v-model="listQuery.search" placeholder="会员组名" style="width: 200px;" size="mini"
                      class="filter-item"></el-input>
            <el-button class="filter-item" style="margin-left: 10px;" size="mini" type="danger" @click="doSearch()">
                搜索
            </el-button>
            <el-button class="filter-item" style="margin-left: 10px;" size="mini" type="danger"
                       @click="delGroup('',true)">
                批量删除
            </el-button>

            <el-button class="filter-item" style="margin-left: 10px;" size="mini" type="primary" @click="listOrder()">
                批量排序
            </el-button>

            <el-button class="filter-item" style="margin-left: 10px;" size="mini" type="primary" @click="addEdit(0)">
                添加会员组
            </el-button>
        </div>
        <el-table size=""
                  :key="tableKey"
                  :data="list"
                  fit
                  highlight-current-row
                  style="width: 100%;"
                  @selection-change="handleSelectionChange"
        >
            <el-table-column
                type="selection"
                width="55"
                label="全选">
            </el-table-column>

            <el-table-column label="ID" width="" align="center">
                <template slot-scope="{row}">
                    <span>{{ row.group_id }}</span>
                </template>
            </el-table-column>

            <el-table-column label="排序" width="" align="center">
                <template slot-scope="{row}">
                    <el-input v-model="row.sort"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="用户组名" width="" align="center">
                <template slot-scope="{row}">
                    <span>{{ row.group_name }}</span>
                </template>
            </el-table-column>

            <el-table-column label="积分小于" width="" align="center" prop="point">
            </el-table-column>

            <el-table-column label="允许上传附件" min-width="120" align="center">
                <template slot-scope="{row}">
                    <span class="el-icon-success" style="color: green;font-size: 24px;"
                          v-if="row.allowattachment == '1'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.allowattachment == '0'"></span>
                </template>
            </el-table-column>

            <el-table-column label="投稿权限" width="" align="center">
                <template slot-scope="{row}">
                    <span class="el-icon-success" style="color: green;font-size: 24px;"
                          v-if="row.allowpost == '1'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.allowpost == '0'"></span>
                </template>
            </el-table-column>

            <el-table-column label="投稿不需审核" min-width="120" align="center">
                <template slot-scope="{row}">
                    <span class="el-icon-success" style="color: green;font-size: 24px;"
                          v-if="row.allowpostverify == '1'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.allowpostverify == '0'"></span>
                </template>
            </el-table-column>

            <el-table-column label="搜索权限" width="" align="center">
                <template slot-scope="{row}">
                    <span class="el-icon-success" style="color: green;font-size: 24px;"
                          v-if="row.allowsearch == '1'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.allowsearch == '0'"></span>
                </template>
            </el-table-column>

            <el-table-column label="自助升级" width="" align="center">
                <template slot-scope="{row}">
                    <span class="el-icon-success" style="color: green;font-size: 24px;"
                          v-if="row.allowupgrade == '1'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.allowupgrade == '0'"></span>
                </template>
            </el-table-column>

            <el-table-column label="发短消息" width="" align="center">
                <template slot-scope="{row}">
                    <span class="el-icon-success" style="color: green;font-size: 24px;"
                          v-if="row.allowsendmessage == '1'"></span>
                    <span class="el-icon-error" style="color: red;font-size: 24px;"
                          v-if="row.allowsendmessage == '0'"></span>
                </template>
            </el-table-column>


            <el-table-column label="操作" width="300px" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                    <el-button type="primary" size="mini" @click="addEdit(row.group_id)">
                        编辑
                    </el-button>
                    <el-button type="danger" size="mini" @click="delGroup(row.group_id,false)">
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
                multipleSelection: [],
                total: 0,
                page_count: 0,
                listQuery: {
                    page: 1,
                    limit: 15,
                    search: ''
                }
            },
            watch: {},
            filters: {
                parseTime: function (time, format) {
                    return Ztbcms.formatTime(time, format)
                },
            },
            methods: {
                // 全选
                handleSelectionChange: function (val) {
                    this.multipleSelection = val;
                    var selectUserIds = [];
                    this.multipleSelection.forEach(function (val) {
                        selectUserIds.push(val.group_id);
                    })
                    this.selectUserIds = selectUserIds;
                },
                doSearch: function () {
                    var that = this;
                    that.listQuery.page = 1;
                    that.getList();
                },
                getList: function () {
                    var that = this;
                    var url = '{:api_url("/member/group/getList")}';
                    var data = that.listQuery;
                    that.httpGet(url, data, function (res) {
                        if (res.status) {
                            that.list = res.data.data;
                            that.total = res.data.total;
                            that.page_count = res.data.per_page;
                        }
                    });
                },
                // 新增编辑
                addEdit: function (id) {
                    var url = '{:api_url("/member/group/detail")}';
                    if (id) url += '&group_id=' + id;

                    var that = this;
                    layer.open({
                        type: 2,
                        title: '详情',
                        content: url,
                        area: ['80%', '90%'],
                        end: function () {  //回调函数
                            that.getList()
                        }
                    })
                },

                // 批量删除
                delGroup: function (groupId, batch) {
                    var _this = this;
                    var groupIds = [];
                    // 批量
                    if (batch == true) {
                        groupIds = this.selectUserIds;
                        if (groupIds.length == 0) {
                            layer.msg('请选择')
                            return false;
                        }
                    } else {
                        // 单次
                        groupIds.push(groupId)
                    }
                    layer.confirm('确定要删除吗？', {}, function () {
                        $.ajax({
                            url: "{:api_url('/member/group/delGroup')}",
                            data: {
                                group_id: groupIds,
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
                },

                // 排序批量
                listOrder: function () {
                    var that = this;
                    var formData = [];
                    this.multipleSelection.forEach(function (val, index) {
                        formData.push({
                            group_id: val.group_id,
                            sort: val.sort,
                        })
                    });
                    if (formData.length > 0) {
                        layer.confirm('确认要进行排序?', function () {
                            $.ajax({
                                url: "{:api_url('/member/group/listOrder')}",
                                type: "post",
                                data: {
                                    data: formData
                                },
                                dataType: "json",
                                success: function (res) {
                                    layer.msg(res.msg)
                                    if (res.status) {
                                        that.getList();
                                    }
                                }
                            })
                        });
                    } else {
                        layer.msg('请选择')
                    }
                },
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>
