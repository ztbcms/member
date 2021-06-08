<style>
    .prompt {
        font-size: 12px;
    }
</style>

<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-row>
            <el-col :span="24">
                <div class="grid-content">
                    <el-form ref="form" :model="form" label-width="120px">

                        <el-form-item label="用户" required>
                            <el-select size="small" v-model="form.user_id" filterable placeholder="请选择">
                                <el-option label="请选择" value=""></el-option>
                                <el-option
                                    v-for="item in member"
                                    :label="'（用户ID :' + item.user_id + '）'+ item.nickname"
                                    :value="item.user_id">
                                </el-option>
                            </el-select>
                            <br>
                            <span class="prompt">用户开发人员的调试</span>
                        </el-form-item>

                        <el-form-item>
                            <el-button size="small" type="primary" @click="onSubmit">生成</el-button>
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
                    user_id : ""
                },
                member : []
            },
            watch: {},
            filters: {},
            methods: {
                onSubmit: function () {
                    var that = this;
                    var url = '{:api_url("/member/admin.Token/details")}';
                    var data = that.form;
                    data._action = 'submit';
                    that.httpPost(url, data, function (res) {
                        if (res.status) {
                            layer.msg('生成成功', {time: 1000}, function () {
                                parent.layer.closeAll();
                            });
                        } else {
                            layer.msg(res.msg, {time: 1000});
                        }
                    });
                },
                getMember: function () {
                    var that = this;
                    var url = '{:api_url("/member/admin.Token/details")}';
                    that.httpPost(url, {
                        '_action': 'member'
                    }, function (res) {
                        if (res.status) {
                            that.member = res.data;
                        }
                    });
                }
            },
            mounted: function () {
                var that = this;
                that.getMember();
            }
        })
    })
</script>
