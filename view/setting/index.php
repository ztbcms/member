<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="18" :md="18">
            <div>
                <el-form ref="elForm" :model="formData" size="medium" label-width="180px">
                    <el-form-item label="通行证设置" required>
                        <el-select v-model="formData.interface">
                            <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>通行证可以放到
                    </el-form-item>

                    <el-form-item label="允许新会员注册">
                        <el-radio-group v-model="formData.allowregister">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="默认注册模型">
                        <el-select v-model="formData.defaultmodelid">

                        </el-select>
                    </el-form-item>

                    <el-form-item label="新会员注册需要邮件验证">
                        <el-radio-group v-model="formData.enablemailcheck">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                        需填写邮箱配置，开启后会员注册审核功能无效
                    </el-form-item>

                    <el-form-item label="新会员注册需要管理员审核">
                        <el-radio-group v-model="formData.registerverify">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="是否启用应用间积分兑换">
                        <el-radio-group v-model="formData.showapppoint">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="1元人民币购买积分数量" >
                        <el-input v-model="formData.rmb_point_rate"
                                  placeholder="" clearable :style="{width: '50%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="新会员默认点数" >
                        <el-input v-model="formData.defualtpoint"
                                  placeholder="" clearable :style="{width: '50%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="新会员注册默认赠送资金" >
                        <el-input v-model="formData.defualtamount"
                                  placeholder="" clearable :style="{width: '50%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="是否显示注册协议">
                        <el-radio-group v-model="formData.showregprotocol">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="是否开启登录验证码">
                        <el-radio-group v-model="formData.openverification">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="会员注册协议" required>
                        <el-input v-model="formData.regprotocol"
                                  type="textarea"
                                  row="6"
                                  placeholder="" clearable :style="{width: '80%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="邮件认证内容" required>
                        <el-input v-model="formData.registerverifymessage"
                                  type="textarea"
                                  row="6"
                                  placeholder="" clearable :style="{width: '80%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="密码找回邮件内容" required>
                        <el-input v-model="formData.forgetpassword"
                                  type="textarea"
                                  row="6"
                                  placeholder="" clearable :style="{width: '80%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item size="large">
                        <el-button type="primary" @click="submitForm">提交</el-button>
                        <el-button @click="resetForm">取消</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-col>
    </el-card>
</div>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            components: {},
            props: [],
            data() {
                return {
                    formData: {
                        interface: '本地用户通行证',
                    }
                }
            },
            computed: {},
            watch: {},
            created() {
            },
            mounted() {
                this.getDetail()
            },
            methods: {
                submitForm: function () {
                    var that = this;
                    var url = "{:api_url('/member/tag/addEdit')}"
                    this.httpPost(url, this.formData, function (res) {
                        if (res.status) {
                            that.$message.success(res.msg);
                            // 关闭窗口
                            if (window !== window.parent) {
                                setTimeout(function () {
                                    window.parent.layer.closeAll()
                                }, 1000);
                            }
                        }else{
                            that.$message.error(res.msg);
                        }
                    });
                },
                getDetail: function () {
                    var url = "{:api_url('/member/setting/setting')}"
                    var _this = this;
                    this.httpGet(url, {}, function (res) {
                        if (res.status) {
                            _this.formData = res.data
                        } else {
                            layer.msg(res.msg);
                        }
                    });
                },
                resetForm: function () {
                    // window.parent.layer.closeAll()
                },
            }
        });
    });
</script>
