<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <h3>添加会员</h3>
        <el-row>
            <el-col :span="20">
                <div class="grid-content ">
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="用户名">
                            <span>{{form.username}}</span>
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

                        <el-form-item label="标签" required>
                            <el-select
                                v-model="tag_ids"
                                multiple
                                filterable
                                allow-create
                                default-first-option
                                @change="selectChange"
                                placeholder="标签,可多选">
                                <el-option
                                    v-for="item in TagsList"
                                    :key="item.tag_id"
                                    :label="item.tag_name"
                                    :value="item.tag_id">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="邮箱">
                            <el-input v-model="form.email"></el-input>
                        </el-form-item>

                        <el-form-item label="积分点数">
                            <el-input v-model="form.point"></el-input>
                        </el-form-item>

                        <el-form-item label="会员组">
                            <el-select v-model="form.group_id">
                                <el-option
                                    v-for="item in group"
                                    :key="item.group_id"
                                    :label="item.group_name"
                                    :value="item.group_id">
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
                group: [],
                TagsList: [],
                form: {
                    username: '',
                    checked: '1',
                    password: '',
                    email: '',
                    password_confirm: '',
                    modelid: '', // 模型
                    info: '', // 模型字段内容
                },
                tag_ids: [],
                user_id: "{:input('user_id')}",
            },
            watch: {
                tag_ids :function(val){
                    var url = '{:api_url("/member/tag/addEdit")}';
                    var that = this;
                    var value = val[val.length-1];
                    // 如果是字符串
                    if(typeof(value)  =='string' ){
                        // 进行添加新标签
                        this.httpPost(url, {tag_name: value }, function(res){
                            if(res.status){
                                that.TagsList.push({ tag_name : value , tag_id: parseInt(res.data) })
                                that.tag_ids[val.length-1] = parseInt(res.data)
                            }else{
                                // 删除
                                that.tag_ids.splice(val.length-1,1)
                                layer.msg(res.msg)
                            }
                        });
                    }
                }
            },
            filters: {},
            methods: {
                selectChange: function (val) {
                    console.log(val)
                },
                // 获取所有标签
                getTagsList: function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/member/tag/getList')}",
                        data: {is_page: ''},
                        method: 'get',
                        success: function (res) {
                            that.TagsList = res.data
                        }
                    })
                },
                // 获取所有会员组
                getGroup: function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/member/group/getGroupList')}",
                        data: {},
                        method: 'get',
                        success: function (res) {
                            that.group = res.data
                        }
                    })
                },
                onSubmit: function () {
                    var formData = this.form;
                    formData.tag_ids = this.tag_ids;
                    $.ajax({
                        url: "{:api_url('/member/user/editUser')}",
                        data: this.form,
                        method: 'post',
                        success: function (res) {
                            layer.msg(res.msg);
                            if (res.status) {
                                // 关闭窗口
                                if (window !== window.parent) {
                                    setTimeout(function () {
                                        window.parent.layer.closeAll()
                                    }, 1000);
                                }
                            }

                        }
                    })
                },
                // 获取详情
                getDetail:function(){
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/member/user/getDetail')}" + "?user_id=" + this.user_id,
                        data: {},
                        method: 'post',
                        success: function (res) {
                            if (res.status) {
                                that.form = res.data
                                that.form.password = null
                                that.form.password_confirm = null
                                that.form.checked = res.data.checked.toString()
                                that.tag_ids = res.data.tag_ids
                            }else{
                                layer.msg(res.msg);
                            }
                        }
                    })
                },
                onCancel: function () {
                    window.parent.layer.closeAll()
                },
            },
            mounted: function () {
                this.getGroup();
                this.getTagsList();
                if (this.user_id > 0) {
                    this.getDetail()
                }
            },

        })
    })
</script>
