<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div slot="header" class="clearfix">
            <span>会员设置</span>
        </div>
        <el-row>
            <el-col :span="24">
                <div class="grid-content">
                    <el-form ref="form" :model="form" label-width="120px">

                        <el-form-item label="拉黑开关" required>
                            <el-radio v-model="form.block_switch" label="1">开启</el-radio>
                            <el-radio v-model="form.block_switch" label="0">关闭</el-radio>
                            <br>
                            <span>开启后，拉黑用户将无法登陆，并返回402</span>
                        </el-form-item>

                        <el-form-item label="审核开关" required>
                            <el-radio v-model="form.audit_switch" label="1">开启</el-radio>
                            <el-radio v-model="form.audit_switch" label="0">关闭</el-radio>
                            <br>
                            <span>开启后，未通过审核用户将无法登陆，并返回403</span>
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
                    'block_switch' : '0',
                    'audit_switch' : '0'
                }
            },
            watch: {},
            filters: {},
            methods: {
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
