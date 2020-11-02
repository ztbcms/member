<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="18" :md="18">
            <div>
                <el-form ref="elForm" :model="formData" size="medium" label-width="100px">
                    <el-form-item label="标签名称" required>
                        <el-input v-model="formData.tag_name"
                                  maxlength="4"
                                  placeholder="请输入标签名称" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>
                    <el-form-item label="排序">
                        <el-input v-model="formData.sort"
                                  type="number"
                                  placeholder="请输入数值，数值越大，排名越靠前" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="显示状态" width="">
                        <template slot-scope="scope">
                            <el-switch
                                v-model="formData.is_show"
                                :active-value="1"
                                :inactive-value="0"/>
                            </el-switch>
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
                        tag_id: "{:input('tag_id')}",
                        sort: 0,
                        is_show: 1,
                    }
                }
            },
            computed: {},
            watch: {},
            created() {
            },
            mounted() {
                if (this.formData.tag_id > 0) {
                    this.getDetail()
                }
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
                    var url = "{:api_url('/member/tag/getDetail')}"
                    var _this = this;
                    this.httpGet(url, {tag_id: this.formData.tag_id}, function (res) {
                        if (res.status) {
                            _this.formData = res.data
                        } else {
                            layer.msg(res.msg);
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
