<style>
    .prompt {
        font-size: 12px;
    }
</style>

<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div slot="header" class="clearfix">
            <span>等级管理</span>
        </div>
        <el-row>
            <el-col :span="24">
                <div class="grid-content">
                    <el-form ref="form" :model="form" label-width="120px">

                        <el-form-item label="等级名称" required>
                            <el-input v-model="form.member_grade_name" size="small" style="width: 400px;"
                                      placeholder="等级名称">
                            </el-input>
                            <br>
                            <span class="prompt">例如：大众会员、黄金会员、铂金会员、钻石会员</span>
                        </el-form-item>

                        <el-form-item label="需要满足的积分" required>
                            <el-input type="number" v-model="form.meet_integration" size="small" style="width: 400px;"
                                      placeholder="需要满足的积分">
                            </el-input>
                            <br>
                            <span class="prompt">用户的实际消费n积分后，自动升级</span>
                        </el-form-item>

                        <el-form-item label="需要满足的金额" required>
                            <el-input type="number" v-model="form.meet_trade" size="small" style="width: 400px;"
                                      placeholder="需要满足的金额">
                            </el-input>
                            <br>
                            <span class="prompt">用户的实际消费金额满n元后，自动升级</span>
                        </el-form-item>

                        <el-form-item label="权重" required>
                            <el-input type="number" v-model="form.member_sort" size="small" style="width: 400px;"
                                      placeholder="权重">
                            </el-input>
                            <br>
                            <span class="prompt">会员等级的权重，数字越大 等级越高</span>
                        </el-form-item>

                        <el-form-item label="等级权益" required>
                            <el-input type="number" v-model="form.discount" size="small" style="width: 400px;"
                                      placeholder="等级权益">
                            </el-input>
                            <span>折</span>
                            <br>
                            <span class="prompt">折扣率范围0-10，9.5代表9.5折，0代表不折扣</span>
                        </el-form-item>

                        <el-form-item label="是否开启" required>
                            <el-radio v-model="form.is_display" label="1">开启</el-radio>
                            <el-radio v-model="form.is_display" label="0">关闭</el-radio>
                        </el-form-item>

                        <el-form-item>
                            <el-button size="small" type="primary" @click="onSubmit">保存</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </el-col>
            <el-col :span="16">
                <div class="grid-content"></div>
            </el-col>
        </el-row>
    </el-card>
</div>

<script>
    $(document).ready(function () {
        window.__app = new Vue({
            el: '#app',
            data: {
                id: "",
                form: {
                    member_grade_id: "{:input('member_grade_id')}",
                    member_grade_name: '',
                    meet_integration: '0',
                    meet_trade: '0',
                    member_sort: '50',
                    discount: '',
                    is_display: '1'
                }
            },
            watch: {},
            filters: {},
            methods: {
                onSubmit: function () {
                    var that = this;
                    var url = '{:api_url("/member/admin.Grade/details")}';
                    var data = that.form;
                    data._action = 'submit';
                    that.httpPost(url, data, function (res) {
                        if (res.status) {
                            layer.msg('提交成功', {time: 1000}, function () {
                                parent.layer.closeAll();
                            });
                        } else {
                            layer.msg(res.msg, {time: 1000});
                        }
                    });
                },
                getDetails: function () {
                    var that = this;
                    var url = '{:api_url("/member/admin.Grade/details")}';
                    that.httpPost(url, {
                        'member_grade_id': "{:input('member_grade_id')}",
                        '_action': 'details'
                    }, function (res) {
                        if (res.status) {
                            that.form = res.data;
                        }
                    });
                }
            },
            mounted: function () {
                var that = this;
                if (that.form.member_grade_id > 0) {
                    that.getDetails();
                }
            }
        })
    })
</script>
