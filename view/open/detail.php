<div>
    <div id="app" v-cloak>
        <el-card>
            <el-row>
                <el-col :span="20">
                    <div slot="header" class="clearfix">
                        <span>添加应用</span>
                    </div>
                    <el-form :model="form" label-width="80px">
                        <el-form-item label="第三方">
                            <el-select v-model="form.app_type">
                                <el-option v-for="item in openTypeList"
                                           :value="item.value"
                                           :label="item.label"
                                           :key="item.value"
                                >
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="app_key">
                            <el-input v-model="form.app_key"></el-input>
                        </el-form-item>
                        <el-form-item label="app_secret">
                            <el-input v-model="form.app_secret"></el-input>
                        </el-form-item>
                        <el-form-item label="" style="margin-top: 10px;padding-top: 10px;">
                            <el-button type="primary" @click="submitEvent">确定</el-button>
                            <el-button type="default" @click="cancelEvent">取消</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
        </el-card>
    </div>
    <script>
        $(document).ready(function () {
            new Vue({
                el: "#app",
                data: {
                    openTypeList: [],
                    form: {
                        id: '',
                        app_type: "",
                        app_key: "",
                        app_secret: "",
                    },
                },
                mounted() {
                    this.form.id = this.getUrlQuery('id');
                    this.getTypeList()
                    this.getDetail()
                },
                methods: {
                    cancelEvent: function () {
                        parent.layer.closeAll()
                    },
                    // 获取平台
                    getTypeList: function () {
                        var _this = this;
                        $.ajax({
                            url: "{:api_url('/member/open/getTypeList')}",
                            data: null,
                            dataType: 'json',
                            type: 'get',
                            success: function (res) {
                                if (res.status) {
                                    _this.openTypeList = res.data;
                                }
                            }
                        })
                    },
                    getDetail: function () {
                        var _this = this;
                        if (!this.form.id) {
                            return
                        }
                        $.ajax({
                            url: "{:api_url('/member/open/getDetail')}",
                            data: {
                                id: _this.form.id
                            },
                            dataType: 'json',
                            type: 'get',
                            success: function (res) {
                                if (res.status) {
                                    _this.form = res.data;
                                }
                            }
                        })
                    },
                    submitEvent: function () {
                        var _this = this;
                        $.ajax({
                            url: "{:urlx('/member/open/addEditApp')}",
                            data: _this.form,
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    layer.msg('操作成功');
                                    setTimeout(function () {
                                        window.parent.layer.closeAll();
                                    }, 2000);
                                } else {
                                    layer.msg(res.msg)
                                }
                            }
                        })
                    }
                }
            })
        });
    </script>
</div>
