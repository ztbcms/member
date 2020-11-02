<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="18" :md="18">
            <div>
                <el-form ref="elForm" :model="formData" size="medium" label-width="180px">
                    <el-form-item label="会员组名称" required>
                        <el-input v-model="formData.group_name"
                                  placeholder="请输入会员组名称" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>
                    <el-form-item label="排序">
                        <el-input v-model="formData.sort"
                                  type="number"
                                  placeholder="请输入数值，数值越大，排名越靠前" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="积分小于">
                        <el-input v-model="formData.point"
                                  type="number"
                                  placeholder="" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="星星数">
                        <el-input v-model="formData.starnum"
                                  type="number"
                                  placeholder="" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="用户权限">
                        <el-checkbox-group v-model="formData.power">
                            <el-checkbox label="allowpost" name="type">允许投稿</el-checkbox>
                            <el-checkbox label="allowpostverify" name="type">投稿不需审核</el-checkbox>
                            <el-checkbox label="allowupgrade" name="type">允许自助升级</el-checkbox>
                            <el-checkbox label="allowsendmessage" name="type">允许发短消息</el-checkbox>
                            <el-checkbox label="allowattachment" name="type">允许上传附件</el-checkbox>
                            <el-checkbox label="allowsearch" name="type">搜索权限</el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>

                    <el-form-item label="日最大投稿数">
                        <el-input v-model="formData.allowpostnum"
                                  type="number"
                                  placeholder="" clearable :style="{width: '50%'}">
                        </el-input>
                        0为不限制
                    </el-form-item>

                    <el-form-item label="用户组图标">
                        <template v-if="formData.icon">
                            <div class="imgListItem">
                                <img :src="formData.icon" style="width: 120px;height: 120px;">
                                <div class="deleteMask" @click="gotoUploadImage">
                                    <span style="line-height: 120px;font-size: 22px" class="el-icon-upload"></span>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="imgListItem">
                                <div @click="gotoUploadImage" style="width: 120px;height: 120px;text-align: center;">
                                    <span style="line-height: 120px;font-size: 22px" class="el-icon-plus"></span>
                                </div>
                            </div>
                        </template>
                    </el-form-item>

                    <el-form-item label="简洁描述">
                        <el-input v-model="formData.description"
                                  type="textarea">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="可以上传图片总数">
                        <el-input v-model="formData.expand.upphotomax"
                                  type="number"
                                  placeholder="" clearable :style="{width: '50%'}">
                        </el-input>
                        0 为不允许上传
                    </el-form-item>

                    <el-form-item label="最大短消息数">
                        <el-input v-model="formData.allowmessage"
                                  type="number"
                                  placeholder="" clearable :style="{width: '100%'}">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="是否可以留言">
                        <el-radio-group v-model="formData.expand.iswall">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="是否可以发送短信息">
                        <el-radio-group v-model="formData.expand.ismsg">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>


                    <el-form-item label="是否可以关注用户">
                        <el-radio-group v-model="formData.expand.isrelatio">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="是否可以添加收藏">
                        <el-radio-group v-model="formData.expand.isfavorite">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="是否可以发表微博">
                        <el-radio-group v-model="formData.expand.isweibo">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="0">否</el-radio>
                        </el-radio-group>
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


<style>

    .demonstration {
        color: red;
    }

    .el-upload__tip {
        line-height: 1.2;
    }

    .imgListItem {
        height: 120px;
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        display: inline-flex;
        margin-right: 10px;
        margin-bottom: 10px;
        position: relative;
        cursor: pointer;
        vertical-align: top;
    }

    .uploadMask {
        position: absolute;
        top: 0;
        left: 0;
        width: 120px;
        height: 120px;
        text-align: center;
        background-color: #fff;
        color: #ddd;
        font-size: 20px;
    }

    .deleteMask {
        position: absolute;
        top: 0;
        left: 0;
        width: 120px;
        height: 120px;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.6);
        color: #fff;
        font-size: 40px;
        opacity: 0;
    }

    .deleteMask:hover {
        opacity: 1;
    }
</style>


<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            components: {},
            props: [],
            data() {
                return {
                    formData: {
                        group_id: "{:input('group_id')}",
                        sort: 0,
                        point: 0,
                        starnum: 0,
                        allowpostnum: 0,
                        description: '',
                        allowmessage: 0,
                        icon: '',
                        power: [],
                        expand: {
                            upphotomax: '0',
                            iswall: '0',
                            ismsg: '0',
                            isrelatio: '0',
                            isfavorite: '0',
                            isweibo: '0',
                        }
                    }
                }
            },
            computed: {},
            watch: {},
            created() {
            },
            mounted() {
                window.addEventListener('ZTBCMS_UPLOAD_IMAGE', this.onUploadedImage.bind(this));
                if (this.formData.group_id > 0) {
                    this.getDetail()
                }
            },
            methods: {
                // 上传图片
                gotoUploadImage: function () {
                    layer.open({
                        type: 2,
                        title: '',
                        closeBtn: false,
                        content: "{:api_url('common/upload.panel/imageUpload')}",
                        area: ['670px', '550px'],
                    })
                },
                onUploadedImage: function (event) {
                    var that = this;
                    var files = event.detail.files;
                    if (files) {
                        that.formData.icon = files[0].fileurl
                    }
                },
                deleteImageItem: function (index) {
                    this.formData.icon = ''
                },

                submitForm: function () {
                    var that = this;
                    var url = "{:api_url('/member/group/addEdit')}"
                    console.log(this.formData)
                    this.httpPost(url, this.formData, function (res) {
                        if (res.status) {
                            that.$message.success(res.msg);
                            // 关闭窗口
                            if (window !== window.parent) {
                                setTimeout(function () {
                                    window.parent.layer.closeAll()
                                }, 1000);
                            }
                        } else {
                            that.$message.error(res.msg);
                        }
                    });
                },
                getDetail: function () {
                    var url = "{:api_url('/member/group/getDetail')}"
                    var _this = this;
                    this.httpGet(url, {group_id: this.formData.group_id}, function (res) {
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
