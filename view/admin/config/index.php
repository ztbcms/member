<style>
    .prompt {
        font-size: 12px;
    }
</style>

<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div slot="header" class="clearfix">
            <span>会员设置</span>
        </div>
        <el-row>
            <el-col :span="24">
                <div class="grid-content">
                    <el-form ref="form" :model="form" label-width="120px">

                        <div style="margin-bottom: 20px;">
                            <template>
                                <el-tabs v-model="activeName" @tab-click="handleClick">
                                    <el-tab-pane label="登录设置" name="login"></el-tab-pane>
                                    <el-tab-pane label="等级设置" name="grade"></el-tab-pane>
                                </el-tabs>
                            </template>
                        </div>

                        <div v-show="activeName == 'login'">
                            <el-form-item label="拉黑开关" required>
                                <el-radio v-model="form.block_switch" label="1">开启</el-radio>
                                <el-radio v-model="form.block_switch" label="0">关闭</el-radio>
                                <br>
                                <span class="prompt">开启后，拉黑用户将无法登陆，并返回402</span>
                            </el-form-item>

                            <el-form-item label="审核开关" required>
                                <el-radio v-model="form.audit_switch" label="1">开启</el-radio>
                                <el-radio v-model="form.audit_switch" label="0">关闭</el-radio>
                                <br>
                                <span class="prompt">开启后，未通过审核用户将无法登陆，并返回403</span>
                            </el-form-item>
                        </div>

                        <div v-show="activeName == 'grade'">
                            <el-form-item label="升级触发条件" required>
                                <el-radio v-model="form.grade_trigger" label="1">消费积分达到设置积分</el-radio>
                                <el-radio v-model="form.grade_trigger" label="2">消费金额达到设置金额</el-radio>
                                <el-radio v-model="form.grade_trigger" label="3">积分，金额同时达到</el-radio>
                                <br>
                                <span class="prompt">用户等级的触发条件</span>
                            </el-form-item>
                        </div>


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
                activeName : 'login',
                id: "",
                form: {
                    'block_switch' : '0',
                    'audit_switch' : '0',
                    'grade_trigger' : '2'
                }
            },
            watch: {},
            filters: {},
            methods: {
                handleClick : function(tab, event) {

                },
                onSubmit: function () {
                    var that = this;
                    var url = '{:api_url("/member/admin.Config/index")}';
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
                    var url = '{:api_url("/member/admin.Config/index")}';
                    that.httpPost(url, {
                        '_action': 'details'
                    }, function (res) {
                        if (res.status) {
                            that.form.block_switch = res.data.block_switch;
                            that.form.audit_switch = res.data.audit_switch;
                            that.form.grade_trigger = res.data.grade_trigger;
                        }
                    });
                }
            },
            mounted: function () {
                var that = this;
                that.getDetails();
            }
        })
    })
</script>
