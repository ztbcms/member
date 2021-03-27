<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-row>
            <el-col :span="10">
                <div class="grid-content ">
                    <el-form ref="form" :model="form" label-width="80px">

                        <el-form-item label="昵称">
                            <el-input v-model="form.nickname"></el-input>
                        </el-form-item>

                        <el-form-item label="用户名">
                            <el-input v-model="form.username"></el-input>
                        </el-form-item>

                        <el-form-item label="密码">
                            <el-input v-model="form.password" type="password"></el-input>
                        </el-form-item>

                        <el-form-item label="确认密码">
                            <el-input v-model="form.password_confirm" type="password"></el-input>
                        </el-form-item>

                        <el-form-item label="邮箱">
                            <el-input v-model="form.email"></el-input>
                        </el-form-item>

                        <el-form-item label="手机号">
                            <el-input v-model="form.phone"></el-input>
                        </el-form-item>

                        <el-form-item label="会员模型">
                            <el-select v-model="form.role_id">
                                <el-option
                                    v-for="item in modelList"
                                    :key="item.modelid"
                                    :label="item.name"
                                    :value="item.modelid">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">保存</el-button>
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

<style>

</style>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                modelList: [],
                form: {
                    user_id: '',
                    username: '',
                    role_id: '1',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirm: '',
                },
            },
            watch: {},
            filters: {},
            computed: {},
            methods: {
                selectChange: function (val) {
                    console.log(val)
                },
                onSubmit: function () {
                    var form = this.form
                    var request_url = ''
                    if(this.form.user_id){
                        form['_action'] = 'editMember'
                        request_url = "{:api_Url('/member/admin.member/editMember')}"
                    } else {
                        form['_action'] = 'addMember'
                        request_url = "{:api_Url('/member/admin.member/addMember')}"
                    }
                    this.httpPost(request_url, form, function (res) {
                        layer.msg(res.msg);
                        if (res.status) {
                            // 关闭窗口
                            if (window !== window.parent) {
                                setTimeout(function () {
                                    window.parent.layer.closeAll()
                                }, 1000);
                            }
                        }
                    })
                },
                // 获取详情
                getDetail: function () {
                    var that = this;
                    var form = {
                        user_id: this.form.user_id,
                        _action: 'getDetail'
                    }
                    this.httpGet("{:api_Url('/member/admin.member/editMember')}",  form, function(res){
                        if (res.status) {
                            that.form = res.data
                        } else {
                            layer.msg(res.msg);
                        }
                    })
                }
            },
            mounted: function () {
                this.form.user_id = this.getUrlQuery('user_id')
                if (this.form.user_id) {
                    this.getDetail()
                }
            }
        })
    })
</script>
