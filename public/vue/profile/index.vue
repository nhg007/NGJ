<template>
    <div class="profile-container">
        <div class="app-main-container">
            <el-form ref="form" :model="form" label-width="120px" v-loading="loading">
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="店名">
                            <el-input v-model="form.name" placeholder="店名"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="ジャンル">
                            <el-select v-model="form.type" placeholder="ジャンル">
                                <el-option
                                    v-for="item in types"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value"
                                >
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="15">
                        <el-form-item label="ホームページ">
                            <el-input
                                v-model="form.homepage"
                                placeholder="ホームページ"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="住所(必需)">
                    <el-row :gutter="20">
                        <el-col :span="3">
                            <el-input v-model="form.post" placeholder="郵便番号"></el-input
                            >
                        </el-col>
                        <el-col :span="5">
                            <el-input v-model="form.pref" placeholder="都道府県"></el-input
                            >
                        </el-col>
                        <el-col :span="5"
                        >
                            <el-input v-model="form.city" placeholder="市区町村"></el-input
                            >
                        </el-col>
                        <el-col :span="6"
                        >
                            <el-input v-model="form.street" placeholder="その他"></el-input
                            >
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item label="担当者">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-input placeholder="代表者（必需）" v-model="form.owner">
                                <template slot="prepend">オーナー</template>
                            </el-input>
                        </el-col>
                        <el-col :span="8">
                            <el-input placeholder="事務担当者" v-model="form.stuff1">
                                <template slot="prepend">料理長</template>
                            </el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item label="Eメール">
                    <el-row :gutter="20">
                        <el-col :span="6">
                            <el-tag>{{ form.email }}</el-tag>
                        </el-col>
                        <el-col :span="6">
                            <el-input placeholder="Fax" v-model="form.fax">
                                <template slot="prepend">Fax</template>
                            </el-input>
                        </el-col>
                    </el-row>
                </el-form-item>

                <el-form-item label="定休日">
                    <el-select
                        v-model="form.workday"
                        style="width: 60%"
                        multiple
                        filterable
                        allow-create
                        default-first-option
                        placeholder="取扱ジビエ"
                    >
                        <el-option
                            v-for="item in workdays"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="取扱ジビエ">
                    <el-select
                        v-model="form.animal"
                        style="width: 60%"
                        multiple
                        filterable
                        allow-create
                        default-first-option
                        placeholder="取扱ジビエ"
                    >
                        <el-option
                            v-for="item in animals"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="販売形式">
                    <el-select
                        v-model="form.paymentType"
                        style="width: 60%"
                        allow-create
                        default-first-option
                        placeholder="販売形式"
                    >
                        <el-option
                            v-for="item in paymentTypes"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PR文">
                    <el-input
                        type="textarea"
                        rows="5"
                        v-model="form.PR"
                        style="width: 60%"
                    ></el-input>
                </el-form-item>
                <el-form-item label="店舗外観">
                    <el-row :gutter="1">
                        <el-col :span="5">
                            <el-upload
                                class="avatar-uploader"
                                action="/api/restrant/uploadPic"
                                :show-file-list="false"
                                :on-success="handleOuterSuccess"
                                :before-upload="beforeAvatarUpload"
                                :file-list="form.outFiles"
                                accept="image/jpeg,image/gif,image/png,image/bmp"
                            >
                                <img v-if="outImageUrl" :src="outImageUrl" class="avatar"/>
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-col>
                        <el-col :span="15">
                            <el-input
                                type="textarea"
                                :rows="7"
                                placeholder="コメントを入力してください"
                                v-model="form.outComment"
                            >
                            </el-input>
                        </el-col>
                    </el-row>

                </el-form-item>
                <el-form-item label="内観">
                    <el-row :gutter="1">
                        <el-col :span="5">
                            <el-upload
                                class="avatar-uploader"
                                action="/api/restrant/uploadPic"
                                :show-file-list="false"
                                :on-success="handleInnerSuccess"
                                :before-upload="beforeAvatarUpload"
                                :file-list="form.innerFiles"
                                accept="image/jpeg,image/gif,image/png,image/bmp"
                            >
                                <img v-if="innerImageUrl" :src="innerImageUrl" class="avatar"/>
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-col>
                        <el-col :span="15">
                            <el-input
                                type="textarea"
                                :rows="7"
                                placeholder="コメントを入力してください"
                                v-model="form.innerComment"
                            >
                            </el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item label="スタッフ">
                    <el-row :gutter="1">
                        <el-col :span="5">
                            <el-upload
                                class="avatar-uploader"
                                action="/api/restrant/uploadPic"
                                :show-file-list="false"
                                :on-success="handleStaffSuccess"
                                :before-upload="beforeAvatarUpload"
                                :file-list="form.staffFiles"
                                accept="image/jpeg,image/gif,image/png,image/bmp"
                            >
                                <img
                                    v-if="staffImageUrl"
                                    :src="staffImageUrl"
                                    class="avatar-square"
                                />
                                <i v-else class="el-icon-plus avatar-uploader-icon-square"></i>
                            </el-upload>
                        </el-col>
                        <el-col :span="15">
                            <el-input
                                type="textarea"
                                :rows="10"
                                placeholder="コメントを入力してください"
                                v-model="form.staffComment"
                            >
                            </el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item label="料理">
                    <el-row :gutter="1">
                        <el-col :span="5">
                            <el-upload
                                class="avatar-uploader"
                                action="/api/restrant/uploadPic"
                                :show-file-list="false"
                                :on-success="handleFoodSuccess"
                                :before-upload="beforeAvatarUpload"
                                :file-list="form.foodFiles"
                                accept="image/jpeg,image/gif,image/png,image/bmp"
                            >
                                <img
                                    v-if="foodImageUrl"
                                    :src="foodImageUrl"
                                    class="avatar-square"
                                />
                                <i v-else class="el-icon-plus avatar-uploader-icon-square"></i>
                            </el-upload>
                        </el-col>
                        <el-col :span="15">
                            <el-input
                                type="textarea"
                                :rows="10"
                                placeholder="コメントを入力してください"
                                v-model="form.foodComment"
                            >
                            </el-input>
                        </el-col>
                    </el-row>
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
                form: {
                    name: "",
                    homepage: "",
                    type: "",
                    paymentType: "",
                    post: "",
                    pref: "",
                    city: "",
                    street: "",
                    email: "",
                    owner: "",
                    stuff1: "",
                    fax: "",
                    PR: "",
                    latitude: "",
                    longitude: "",
                    ranking: 0,
                    comment: "",
                    animal: [],
                    workday: [],
                    outFiles: [],
                    outComment: '',
                    innerFiles: [],
                    innerComment: '',
                    staffFiles: [],
                    staffComment: '',
                    foodFiles: [],
                    foodComment: ''
                },
                types: [
                    {
                        value: "イタリアン",
                        label: "イタリアン",
                    },
                    {
                        value: "フレンチ",
                        label: "フレンチ",
                    },
                    {
                        value: "中華料理",
                        label: "中華料理",
                    },
                    {
                        value: "日本料理",
                        label: "日本料理",
                    },
                    {
                        value: "その他",
                        label: "その他",
                    },
                ],
                paymentTypes: [
                    {
                        value: "イートイン",
                        label: "イートイン",
                    },
                    {
                        value: "現地決済",
                        label: "現地決済",
                    },
                    {
                        value: "郵送",
                        label: "郵送",
                    },
                ],
                animals: [
                    {
                        value: "ホンシュウ鹿",
                        label: "ホンシュウ鹿",
                    },
                    {
                        value: "猪",
                        label: "猪",
                    },
                    {
                        value: "ツキノワグマ",
                        label: "ツキノワグマ",
                    },
                    {
                        value: "ヒグマ",
                        label: "ヒグマ",
                    },
                    {
                        value: "アナグマ",
                        label: "アナグマ",
                    },
                    {
                        value: "ハクビシン",
                        label: "ハクビシン",
                    },
                    {
                        value: "兎",
                        label: "兎",
                    },
                    {
                        value: "ヌートリア",
                        label: "ヌートリア",
                    },
                    {
                        value: "タヌキ",
                        label: "タヌキ",
                    },
                    {
                        value: "キョン",
                        label: "キョン",
                    },
                    {
                        value: "エゾ鹿",
                        label: "エゾ鹿",
                    },
                    {
                        value: "トド",
                        label: "トド",
                    },
                    {
                        value: "鴨",
                        label: "鴨",
                    },
                    {
                        value: "カラス",
                        label: "カラス",
                    },
                    "カラス",
                ],
                workdays: [
                    {
                        value: "日",
                        label: "日曜",
                    },
                    {
                        value: "月",
                        label: "月曜",
                    },
                    {
                        value: "火",
                        label: "火曜",
                    },
                    {
                        value: "水",
                        label: "水曜",
                    },
                    {
                        value: "木",
                        label: "木曜",
                    },
                    {
                        value: "金",
                        label: "金曜",
                    },
                    {
                        value: "土",
                        label: "土曜",
                    },
                    {
                        value: "祝",
                        label: "祝日",
                    },
                    {
                        value: "不",
                        label: "不定",
                    },
                ],
                outImageUrl: "",
                innerImageUrl: "",
                staffImageUrl: "",
                foodImageUrl:"",
                loading: true,
                total: 0,
                height: 0,
            };
        },
        created() {
            this.height = document.body.clientHeight - 300;
            this.fetchData();
        },
        mounted() {
            const that = this;
            window.onresize = function temp() {
                that.height = document.body.clientHeight - 300;
            };
        },
        filters: {

        },
        methods: {
            dateFormat(row, column, cellValue) {
                return moment(cellValue).format("yyyy-MM-DD HH:mm");
            },
            queryData() {
                this.fetchData();
            },
            fetchData() {
                let _this = this;
                axios
                    .get("/restrant/info")
                    .then(function (response) {
                        _this.loading = false;
                        const {status, message, data} = response;
                        if (status === 200) {
                            const {user, images} = data;
                            _this.form.name = user.name;
                            _this.form.homepage = user.homepage;
                            _this.form.type = user.type;
                            _this.form.paymentType = user.paymentType;
                            _this.form.post = user.post;
                            _this.form.pref = user.pref;
                            _this.form.city = user.city;
                            _this.form.street = user.street;
                            _this.form.email = user.email;
                            _this.form.owner = user.owner;
                            _this.form.stuff1 = user.stuff1;
                            _this.form.fax = user.fax;
                            _this.form.PR = user.PR;
                            _this.form.latitude = user.latitude;
                            _this.form.latitude = user.latitude;
                            _this.form.comment = user.comment;
                            _this.form.workday = (user.workday || "").split(",");
                            _this.form.animal = (user.animal || "").split(",");
                            _this.form.id = user.id;
                            _this.form.ranking = user.ranking || 0;
                            if (images) {
                                //外图
                                if (images.outPic) {
                                    _this.outImageUrl = "/uploads/" + images.outPic;
                                    _this.form.outFiles = images.outPic;
                                }
                                _this.form.outComment = images.outComment
                                //内图
                                if (images.innerPic) {
                                    _this.innerImageUrl = "/uploads/" + images.innerPic;
                                    _this.form.innerFiles = images.innerPic;
                                }
                                _this.form.innerComment = images.innerComment
                                //スッタフ
                                if (images.staffPic) {
                                    _this.staffImageUrl = "/uploads/" + images.staffPic;
                                    _this.form.staffFiles = images.staffPic;
                                }
                                _this.form.staffComment = images.staffComment
                                //料理
                                if (images.foodPic) {
                                    _this.foodImageUrl = "/uploads/" + images.foodPic;
                                    _this.form.foodFiles = images.foodPic;
                                }
                                _this.form.foodComment = images.foodComment
                            }
                        } else {
                            _this.$message.error(message);
                        }
                    })
                    .catch(function (error) {
                        _this.loading = false;
                        _this.$message.error("削除失敗しました");
                    });
            },
            onSubmit() {
                let _this = this;
                let url = "/restrant/update";

                let postData = Object.assign({}, _this.form);

                postData.workday = postData.workday.join(",");
                postData.animal = postData.animal.join(",");
                postData.outFiles = [postData.outFiles].join(",");
                postData.innerFiles = [postData.innerFiles].join(",");
                postData.staffFiles = [postData.staffFiles].join(",");
                postData.foodFiles = [postData.foodFiles].join(",")

                //経緯度を取得する

                var geocoder = new google.maps.Geocoder();
                // ジオコーディング　検索実行
                geocoder.geocode(
                    {
                        address:
                            _this.form.post +
                            _this.form.pref +
                            _this.form.city +
                            _this.form.street,
                    },
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var lat = results[0].geometry.location.lat(); //緯度を取得
                            var lng = results[0].geometry.location.lng(); //経度を取得
                            postData.latitude = lat;
                            postData.longitude = lng;
                        }

                        axios
                            .post(url, postData)
                            .then(function (response) {
                                const {status, message, data} = response.data;
                                _this.$message.success(message);
                                console.info(response.data);
                            })
                            .catch(function (error) {
                                _this.$message.error("操作を失敗しました");
                            });
                    }
                );
            },
            handleOuterSuccess(res, file) {
                this.form.outFiles = res.photo;
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
            handleFoodSuccess(res, file) {
                this.form.foodFiles = res.photo;
                this.foodImageUrl = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === "image/jpeg";
                const isGIF = file.type === "image/gif";
                const isPNG = file.type === "image/png";
                const isBMP = file.type === "image/bmp";
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isGIF && !isPNG && !isBMP) {
                    this.common.errorTip("上传图片必须是JPG/GIF/PNG/BMP 格式!");
                }
                if (!isLt2M) {
                    this.common.errorTip("上传图片大小不能超过 2MB!");
                }
                return (isJPG || isBMP || isGIF || isPNG) && isLt2M;
            },
        },
    };
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
        border-color: #409eff;
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
