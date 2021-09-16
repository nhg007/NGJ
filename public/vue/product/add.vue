<template>
  <el-dialog
    :title="title"
    :visible.sync="dialogFormVisible"
    width="800px"
    top="3vh"
  >
    <el-form :model="form" :rules="rules" ref="form" v-loading="loading">
      <el-form-item label="商品名" :label-width="formLabelWidth" prop="name">
        <el-input v-model="form.name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="内容量" :label-width="formLabelWidth" prop="column">
        <el-input v-model="form.column" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="価格" :label-width="formLabelWidth" prop="price">
        <el-input v-model.number="form.price" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="数量" :label-width="formLabelWidth" prop="number">
        <el-input-number
          v-model.number="form.number"
          :min="0"
          :max="999"
          autocomplete="off"
          style="width: 200px"
        ></el-input-number>
      </el-form-item>
      <el-form-item label="在庫状況" :label-width="formLabelWidth">
        <el-select
          v-model="form.stock_status"
          placeholder="在庫状況を選択してください"
        >
          <el-option label="あり" value="1"></el-option>
          <el-option label="残りわずか" value="2"></el-option>
          <el-option label="売り切れ" value="3"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="画像1" :label-width="formLabelWidth">
        <el-row :gutter="4">
          <el-col :span="6">
            <el-upload
              class="avatar-uploader"
              action="/api/restrant/product/uploadPic"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload"
              :file-list="form.files"
              accept="image/jpeg,image/gif,image/png,image/bmp"
            >
              <img v-if="imageUrl" :src="imageUrl" class="avatar" />
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-col>
          <el-col :span="17">
            <el-input
              type="textarea"
              :rows="7"
              placeholder="コメントを入力してください"
              v-model="form.pic_remark"
            >
            </el-input>
          </el-col>
        </el-row>
      </el-form-item>
      <el-form-item label="画像2" :label-width="formLabelWidth">
       <el-row :gutter="4">
          <el-col :span="6">
            <el-upload
              class="avatar-uploader"
              action="/api/restrant/product/uploadPic"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload"
              :file-list="form.files2"
              accept="image/jpeg,image/gif,image/png,image/bmp"
            >
              <img v-if="imageUrl2" :src="imageUrl2" class="avatar" />
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-col>
          <el-col :span="17">
            <el-input
              type="textarea"
              :rows="7"
              placeholder="コメントを入力してください"
              v-model="form.pic_remark2"
            >
            </el-input>
          </el-col>
        </el-row>
      </el-form-item>
      <el-form-item label="画像3" :label-width="formLabelWidth">
        <el-row :gutter="4">
          <el-col :span="6">
            <el-upload
              class="avatar-uploader"
              action="/api/restrant/product/uploadPic"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload"
              :file-list="form.files3"
              accept="image/jpeg,image/gif,image/png,image/bmp"
            >
              <img v-if="imageUrl3" :src="imageUrl3" class="avatar" />
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-col>
          <el-col :span="17">
            <el-input
              type="textarea"
              :rows="7"
              placeholder="コメントを入力してください"
              v-model="form.pic_remark3"
            >
            </el-input>
          </el-col>
        </el-row>
      </el-form-item>
      <el-form-item label="画像4" :label-width="formLabelWidth">
       <el-row :gutter="4">
          <el-col :span="6">
            <el-upload
              class="avatar-uploader"
              action="/api/restrant/product/uploadPic"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload"
              :file-list="form.files4"
              accept="image/jpeg,image/gif,image/png,image/bmp"
            >
              <img v-if="imageUrl4" :src="imageUrl4" class="avatar" />
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-col>
          <el-col :span="17">
            <el-input
              type="textarea"
              :rows="7"
              placeholder="コメントを入力してください"
              v-model="form.pic_remark4"
            >
            </el-input>
          </el-col>
        </el-row>
      </el-form-item>
      <el-form-item label="詳細内容" :label-width="formLabelWidth">
        <el-input
          type="textarea"
          v-model="form.description"
          rows="5"
        ></el-input>
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="resetForm">リセット</el-button>
      <el-button type="primary" @click="submitForm">確 定</el-button>
    </div>
  </el-dialog>
</template>

<script>
module.exports = {
  name: "ProductAdd",
  data() {
    let checkPrice = function (rule, value, callback) {
      if (!value) {
        return callback(new Error("価格を入力してくだい"));
      }
      if (!Number.isInteger(value)) {
        return callback(new Error("数字を入力してくだい"));
      }
      return callback();
    };
    return {
      dialogFormVisible: false,
      form: {
        id: 0,
        name: "",
        column: "",
        price: "",
        stock_status: "1",
        number: "0",
        description: "",
        files: [],
        picture_path: "",
        original_name: "",
        files2: [],
        pic_remark: "",
        picture_path2: "",
        pic_remark2: "",
        files3: [],
        picture_path3: "",
        pic_remark3: "",
        files4: [],
        picture_path4: "",
        pic_remark4: "",
      },
      title: "",
      formLabelWidth: "80px",
      loading: false,
      rules: {
        name: [
          {
            required: true,
            message: "商品名を入力してくだい",
            trigger: "blur",
          },
        ],
        column: [
          {
            required: true,
            message: "内容量を入力してくだい",
            trigger: "blur",
          },
        ],
        price: [
          { required: true, message: "価格を入力してくだい", trigger: "blur" },
          { validator: checkPrice, trigger: "blur" },
        ],
        number: [
          {
            required: true,
            message: "販売数量を入力してくだい",
            trigger: "blur",
          },
        ],
      },
      imageUrl: "",
      imageUrl2: "",
      imageUrl3: "",
      imageUrl4: "",
    };
  },
  methods: {
    showDialog(row) {
      if (!row) {
        this.title = "商品新規";
        this.form = {
          id: 0,
          name: "",
          column: "",
          price: "",
          number: "",
          stock_status: "1",
          description: "",
          files: [],
          picture_path: "",
          original_name: "",
        };
        this.imageUrl = "";
      } else {
        this.title = "商品編集";
        this.form = Object.assign({}, row);
        this.imageUrl = "/uploads/" + this.form.picture_path;
      }
      this.dialogFormVisible = true;
    },
    submitForm() {
      let _this = this;
      this.$refs["form"].validate((valid) => {
        if (valid) {
          _this.loading = true;
          let url = "/restrant/product/save";
          axios
            .post(url, _this.form)
            .then(function (response) {
              _this.loading = false;
              const { status, message, data } = response.data;
              _this.$message.success(message);
              _this.$emit("fetch-data");
              _this.dialogFormVisible = false;
            })
            .catch(function (error) {
              _this.loading = false;
              _this.$message.error("操作を失敗しました");
            });
        } else {
        }
      });
    },
    resetForm() {
      this.$refs["form"].resetFields();
    },
    handleAvatarSuccess(res, file) {
      this.form.files = [{ name: res.name, url: res.photo }];
      this.imageUrl = URL.createObjectURL(file.raw);
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

<style scoped>
.el-select {
  display: block;
}

.el-input-number {
  display: block;
  width: 100%;
}

.el-input-number .el-input__inner {
  text-align: left;
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
  width: 160px;
  height: 160px;
  line-height: 160px;
  text-align: center;
}
.avatar {
  width: 160px;
  height: 160px;
  display: block;
}
</style>
