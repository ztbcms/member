<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <h3>添加会员</h3>
        <el-row>
            <el-col :span="20">
                <div class="grid-content ">
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="用户名">
                            <el-input v-model="form.username"></el-input>
                        </el-form-item>
                        <el-form-item label="审核通过">
                            <el-radio-group v-model="form.checked">
                                <el-radio label="1">审核通过</el-radio>
                                <el-radio label="0">待审核</el-radio>
                            </el-radio-group>
                        </el-form-item>

                        <el-form-item label="密码">
                            <el-input v-model="form.password" type="password"></el-input>
                        </el-form-item>

                        <el-form-item label="确认密码">
                            <el-input v-model="form.password_confirm" type="password"></el-input>
                        </el-form-item>

                        <el-form-item label="昵称">
                            <el-input v-model="form.nickname"></el-input>
                        </el-form-item>

                        <el-form-item label="邮箱">
                            <el-input v-model="form.email"></el-input>
                        </el-form-item>

                        <el-form-item label="积分点数">
                            <el-input v-model="form.point"></el-input>
                        </el-form-item>

                        <el-form-item label="会员组">
                            <el-select v-model="form.groupid">
                                <el-option
                                    v-for="item in group"
                                    :key="item.groupid"
                                    :label="item.name"
                                    :value="item.groupid">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">添加</el-button>
                            <el-button @click="onCancel">取消</el-button>
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
                group:[],
                user_id : "{:input('user_id')}",
                form: {
                    username: '',
                    checked: '1',
                    password: '',
                    email: '',
                    password_confirm: '',
                    modelid: '', // 模型
                    info: '' , // 模型字段内容
                }
            },
            watch: {},
            filters: {},
            methods: {
                getGroup:function(){
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/member/group/getGroupList')}",
                        data:{},
                        method:'get',
                        success:function(res){
                            that.group = res.data
                        }
                    })
                },
                onSubmit: function () {
                    console.log(this.form)
                    $.ajax({
                        url: "{:api_url('/member/user/addUser')}",
                        data: this.form,
                        method:'post',
                        success:function(res){
                            layer.msg(res.msg);
                            // 关闭窗口
                            if (window !== window.parent) {
                                setTimeout(function () {
                                    window.parent.layer.closeAll()
                                }, 1000);
                            }
                        }
                    })
                },
                onCancel: function () {
                    window.parent.layer.closeAll()
                },
            },
            mounted: function () {
                // 获取会员组
                this.getGroup();
                if(this.user_id > 0){
                    this.getDetail()
                }
            },

        })
    })
</script>
