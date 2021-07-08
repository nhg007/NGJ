<template>
    <div class="profile-container">
        <div class="app-main-container">
            <el-form ref="form" :model="form" label-width="120px" v-loading="loading">
                <el-form-item label="店名">
                    <el-tag>{{ name }}</el-tag>
                </el-form-item>
                <el-form-item label="ジャンル">
                    <el-tag>{{ type }}</el-tag>
                </el-form-item>
                <el-form-item label="郵便番号">
                    <el-tag>〒 {{ post }}</el-tag>
                </el-form-item>
                <el-form-item label="住宅地">
                    <el-tag>{{ pref }}</el-tag>
                    <el-tag>{{ city }}</el-tag>
                    <el-tag>{{ street }}</el-tag>
                </el-form-item>
                <el-form-item label="Eメール">
                    <el-tag>{{ email }}</el-tag>
                </el-form-item>
                <el-form-item label="取扱ジビエ">
                    <el-tag>{{ animal }}</el-tag>
                </el-form-item>
                <el-form-item label="店舗外観">
                    <el-upload
                        class="avatar-uploader"
                        action="/api/restrant/uploadPic"
                        :show-file-list="false"
                        :on-success="handleOuterSuccess"
                        :before-upload="beforeAvatarUpload"
                        :file-list="form.outFiles"
                        accept="image/jpeg,image/gif,image/png,image/bmp">
                        <img v-if="outImageUrl" :src="outImageUrl" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="内観">
                    <el-upload
                        class="avatar-uploader"
                        action="/api/restrant/uploadPic"
                        :show-file-list="false"
                        :on-success="handleInnerSuccess"
                        :before-upload="beforeAvatarUpload"
                        :file-list="form.innerFiles"
                        accept="image/jpeg,image/gif,image/png,image/bmp">
                        <img v-if="innerImageUrl" :src="innerImageUrl" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="スタッフ">
                    <el-upload
                        class="avatar-uploader"
                        action="/api/restrant/uploadPic"
                        :show-file-list="false"
                        :on-success="handleStaffSuccess"
                        :before-upload="beforeAvatarUpload"
                        :file-list="form.staffFiles"
                        accept="image/jpeg,image/gif,image/png,image/bmp">
                        <img v-if="staffImageUrl" :src="staffImageUrl" class="avatar-square">
                        <i v-else class="el-icon-plus avatar-uploader-icon-square"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">保存</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                name: '',
                type: '',
                post: '',
                pref: '',
                city: '',
                street: '',
                email: '',
                animal: '',
                form: {
                    outFiles: null,
                    innerFiles: null,
                    staffFiles: null
                },
                outImageUrl: '',
                innerImageUrl: '',
                staffImageUrl: '',
                loading: true,
                total: 0,
                height: 0
            }
        },
        created() {
            this.height = document.body.clientHeight - 300
            this.fetchData();
        },
        mounted() {
            const that = this;
            window.onresize = function temp() {
                that.height = document.body.clientHeight - 300
            };
        },
        filters: {
            numberFormat(cellValue) {
                return '¥' + (cellValue || 0).toString().replace(/(\d)(?=(?:\d{3})+$)/g, '$1,');
            }
        },
        methods: {
            dateFormat(row, column, cellValue) {
                return moment(cellValue).format('yyyy-MM-DD HH:mm')
            },
            numberFormat(row, column, cellValue) {
                return '¥' + this.$number_format(cellValue)
            },
            queryData() {
                this.fetchData()
            },
            fetchData() {
                let _this = this
                axios.get('/restrant/info').then(function (response) {
                    _this.loading = false
                    const {status, message, data} = response;
                    if (status === 200) {
                        const {user,images} = data
                        _this.name = user.name
                        _this.type = user.type
                        _this.post = user.post
                        _this.pref = user.pref
                        _this.city = user.city
                        _this.street = user.street
                        _this.email = user.email
                        _this.animal = user.animal
                        _this.form.id = user.id
                        if(images){
                            //外图
                            if(images.outPic){
                                _this.outImageUrl = '/uploads/' + images.outPic
                            }
                            //内图
                            if(images.innerPic){
                                _this.innerImageUrl = '/uploads/' + images.innerPic
                            }
                            //外图
                            if(images.staffPic){
                                _this.staffImageUrl = '/uploads/' + images.staffPic
                            }
                        }

                    } else {
                        _this.$message.error(message);
                    }
                })
                    .catch(function (error) {
                        _this.loading = false
                        _this.$message.error('削除失敗しました');
                    });

            },
            onSubmit() {
                let _this = this;
                let url = '/restrant/update';
                axios.post(url, _this.form)
                    .then(function (response) {
                        const {status, message, data} = response.data;
                        _this.$message.success(message);
                        console.info(response.data)
                    })
                    .catch(function (error) {
                        _this.$message.error('操作を失敗しました');
                    });
            },
            handleOuterSuccess(res, file) {
                this.form.outFiles = res.photo
                this.outImageUrl = URL.createObjectURL(file.raw);
            },
            handleInnerSuccess(res, file) {
                this.form.innerFiles = res.photo;
                this.innerImageUrl = URL.createObjectURL(file.raw);
            },
            handleStaffSuccess(res, file) {
                this.form.staffFiles = res.photo;
                this.staffImageUrl = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isGIF = file.type === 'image/gif';
                const isPNG = file.type === 'image/png';
                const isBMP = file.type === 'image/bmp';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isGIF && !isPNG && !isBMP) {
                    this.common.errorTip('上传图片必须是JPG/GIF/PNG/BMP 格式!');
                }
                if (!isLt2M) {
                    this.common.errorTip('上传图片大小不能超过 2MB!');
                }
                return (isJPG || isBMP || isGIF || isPNG) && isLt2M;
            }
        },
    }
</script>
<style>
    .el-form {
        padding-top: 20px;
    }

    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 220px;
        height: 123px;
        line-height: 123px;
        text-align: center;
    }

    .avatar-uploader-icon-square {
        font-size: 28px;
        color: #8c939d;
        width: 220px;
        height: 220px;
        line-height: 220px;
        text-align: center;
    }

    .avatar {
        width: 220px;
        height: 123px;
        display: block;
    }

    .avatar-square {
        width: 220px;
        height: 220px;
        line-height: 220px;
        display: block;
    }
</style>
