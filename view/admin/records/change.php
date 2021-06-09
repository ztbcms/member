<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content">
                        <el-form ref="form" :model="form" label-width="160px">

                            <el-form-item label="变动类型" label-width="120px" required>
                                <el-radio v-model="form.type" label="income">增加</el-radio>
                                <el-radio v-model="form.type" label="pay">减少</el-radio>
                            </el-form-item>

                            <el-form-item label="变动数量" label-width="120px" required>
                                <el-input type="number" v-model="form.val" style="width: 400px"
                                          placeholder="变动数量"></el-input>
                            </el-form-item>

                            <el-form-item label="变动备注" label-width="120px" required>
                                <el-input v-model="form.remark" style="width: 400px" placeholder="变动备注"></el-input>
                            </el-form-item>

                            <el-form-item label-width="120px" required>
                                <el-button type="success" @click="saveContent()">确定</el-button>
                            </el-form-item>


                        </el-form>
                    </div>
                </el-col>
                <el-col :span="16">
                    <div class="grid-content "></div>
                </el-col>
            </el-row>
        </el-card>
    </div>

    <script>
        $(document).ready(function () {
            window.__app = new Vue({
                el: '#app',
                data: {
                    form: {
                        user_id: '{:input("get.user_id")}',
                        type: 'income',
                        val: 0,
                        remark: '',
                        model: '{:input("get.model")}'
                    },
                    props: {
                        checkStrictly: true,
                        lazy: true
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    onCancel: function () {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    },
                    saveContent: function (audit_status) {
                        var that = this;
                        var url = '{:api_url("member/admin.Records/change")}';
                        layer.confirm('您确定进行此操作？', {
                            btn: ['确定', '取消'] //按钮
                        }, function () {
                            var data = that.form;
                            data._action = 'submit';
                            that.httpPost(url, data, function (res) {
                                if (res.status) {
                                    layer.msg('操作成功', {icon: 1});
                                    that.onCancel();
                                } else {
                                    layer.msg(res.msg, {icon: 2});
                                }
                            });
                        });
                    }
                },
                mounted: function () {

                }
            })
        })
    </script>

