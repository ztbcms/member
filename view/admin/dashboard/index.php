<div id="app" style="padding: 8px;" v-cloak>
    <el-row :gutter="24">
        <!--  左侧  -->
        <el-col :span="24">
            <!-- 后台统计  -->
            <el-row :gutter="24" style="margin-top: 20px;">
                <el-col :span="24">
                    <el-card body-style="height:130px; " class="card-summary">
                        <div class="card-summary-label">统计</div>
                        <div class="card-summary-data">
                            <el-row>
                                <el-col :span="8" class="col-data">
                                    <div class="data-value">
                                        <template v-if="adminStatisticsInfo.total_member > -1 ">
                                            {{adminStatisticsInfo.total_member}}
                                        </template>
                                        <template v-else>
                                            -
                                        </template>
                                    </div>
                                    <div class="data-label">会员数总数</div>
                                </el-col>
                                <el-col :span="8" class="col-data">
                                    <div class="data-value">
                                        <template v-if="adminStatisticsInfo.today_new_member > -1">
                                            {{adminStatisticsInfo.today_new_member}}
                                        </template>
                                        <template v-else>
                                            -
                                        </template>
                                    </div>
                                    <div class="data-label">今日新增</div>
                                </el-col>
                                <el-col :span="8" class="col-data">
                                    <div class="data-value">
                                        <template v-if="adminStatisticsInfo.last_sevent_day_new_member > -1">
                                            {{adminStatisticsInfo.last_sevent_day_new_member}}
                                        </template>
                                        <template v-else>
                                            -
                                        </template>
                                    </div>
                                    <div class="data-label">最近7日新增</div>
                                </el-col>
                            </el-row>
                        </div>

                    </el-card>
                </el-col>
            </el-row>

        </el-col>

    </el-row>
</div>

<style>

    /* 后台统计  */
    .card-summary .card-summary-label {
        font-size: 14px;
        color: #333333;
        font-weight: bold;
    }

    .card-summary .card-summary-data {
        margin: 35px 0px 20px;
    }

    .card-summary .col-data {
        text-align: center;
    }

    .card-summary .data-value {
        font-size: 24px;
        color: #409eff;
        font-weight: bold;
    }

    .card-summary .data-label {
        margin-top: 10px;
        font-size: 14px;
        color: #333333;
        font-weight: bold;
    }

    /* 后台统计 */

    /*常用功能*/
    .card-changyong .card-changyong-label {
        font-size: 14px;
        color: #333333;
        font-weight: bold;
    }

    .card-changyong .card-changyong-data {
        margin: 35px 0px 20px;
        text-align: center;
    }

    .card-changyong .col-data {
        cursor: pointer;
    }

    .card-changyong .item-icon {
        display: inline-block;
        border-radius: 4px;
        background: #409eff;
        width: 54px;
        height: 54px;
        text-align: center;
        line-height: 54px;
        color: white;
        font-size: 28px;
    }

    .card-changyong .item-icon i {
        font-size: 28px;
    }

    .card-changyong .item-label {
        font-size: 12px;
        color: #666;
        margin-top: 8px;
    }

    /*常用功能*/

    .system_info .el-form-item__label {
        font-weight: bold;
    }
</style>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                adminStatisticsInfo: {},
            },
            watch: {},
            filters: {},
            methods: {
                getInfo: function () {
                    var that = this;
                    that.httpGet("{:api_url('member/admin.Dashboard/index')}", {'_action': 'getDashboardIndexInfo'}, function (res) {
                        var data = res.data;
                        that.adminStatisticsInfo = data.admin_statistics_info
                    })
                }
            },
            mounted: function () {
                this.getInfo();
            }
        })
    })
</script>
