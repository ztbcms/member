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
                            <el-input :disabled="form.user_id > 0" v-model="form.username"></el-input>
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

                        <el-form-item label="角色">
                            <el-select v-model="form.role_id">
                                </el-option>
                                <el-option
                                    v-for="item in roleList"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
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
                form: {
                    user_id: '',
                    username: '',
                    role_id: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirm: '',
                },
                roleList: []
            },
            watch: {},
            filters: {},
            computed: {
                request_url: function(){
                    return this.form.user_id ? "{:api_Url('/member/admin.member/editMember')}" : "{:api_Url('/member/admin.member/addMember')}"
                }
            },
            methods: {
                selectChange: function (val) {
                    console.log(val)
                },
                onSubmit: function () {
                    var form = this.form
                    if(this.form.user_id){
                        form['_action'] = 'editMember'
                    } else {
                        form['_action'] = 'addMember'
                    }
                    this.httpPost(this.request_url, form, function (res) {
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
                },
                //获取所有角色
                getRoleList: function () {
                    var that = this
                    this.httpGet(this.request_url, {_action: 'getRoleList'}, function(res){
                        if (res.status) {
                            that.roleList = res.data
                        }
                    })
                }
            },
            mounted: function () {
                this.getRoleList()
                this.form.user_id = this.getUrlQuery('user_id')
                if (this.form.user_id) {
                    this.getDetail()
                }
            }
        })
    })
</script>
