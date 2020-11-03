<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="18" :md="18">
            <div>
                <el-form ref="elForm" :model="formData" size="medium" label-width="100px">
                    <el-form-item label="模型名称" required>
                        <el-input v-model="formData.name"
                                  placeholder="请输入模型名称" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="模型表名" required>
                        {$prefix}member_
                        <el-input v-model="formData.tablename"
                                  placeholder="请输入模型表名" clearable :style="{width: '50%'}"
                                  :disabled="formData.modelid > 0"
                        >
                        </el-input>
                    </el-form-item>

                    <el-form-item label="模型描述" required>
                        <el-input v-model="formData.description"
                                  placeholder="请输入模型描述" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="是否禁用" width="" v-if="formData.modelid">
                        <template slot-scope="scope">
                            <el-radio-group v-model="formData.disabled">
                                <el-radio label="1">禁用</el-radio>
                                <el-radio label="0">不禁用</el-radio>
                            </el-radio-group>
                        </template>
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
                        modelid: "",
                        name: "",
                        tablename: "",
                        description: "",
                        disabled: "0",
                    }
                }
            },
            computed: {},
            watch: {},
            created() {
            },
            mounted() {
                this.formData.modelid = this.getUrlQuery('model_id');
                if (this.formData.modelid > 0) {
                    this.getDetail()
                }
            },
            methods: {
                submitForm: function () {
                    var that = this;
                    var url = "{:api_url('/member/model/addEditModel')}"
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
                    var url = "{:api_url('/member/model/getDetail')}"
                    var _this = this;
                    this.httpGet(url, { model_id: this.formData.modelid}, function (res) {
                        if (res.status) {
                            _this.formData = res.data
                            _this.formData.disabled = res.data.disabled.toString()
                        }else{
                            _this.$message.error(res.msg);
                        }
                    });
                },
                resetForm: function () {
                    window.parent.layer.closeAll()
                },
            }
        });
    });
</script>
