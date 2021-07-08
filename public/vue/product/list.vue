<template>
    <div class="product-container">
        <div class="tools">
            <el-button type="primary" icon="el-icon-plus" @click="handleClick()">新規</el-button>
        </div>
        <div class="app-main-container">
            <el-table
                v-loading="loading"
                :data="tableData"
                border
                stripe
                :height="height"
                style="width: 100%">
                <el-table-column
                    fixed
                    :formatter="dateFormat"
                    prop="updated_at"
                    label="日付">
                </el-table-column>
                <el-table-column
                    prop="name"
                    width="300"
                    label="商品名">
                    <template slot-scope="scope">
                        <img v-if="scope.row.picture_path" :src="'/uploads/' + scope.row.picture_path" style="width: 50px;height: 50px;border: 1px solid #ccc;float: left;margin-right: 10px">
                        {{ scope.row.name }}
                    </template>
                </el-table-column>
                <el-table-column
                    prop="column"
                    label="内容量">
                </el-table-column>
                <el-table-column
                    width="80"
                    prop="price"
                    :formatter="numberFormat"
                    label="価格">
                </el-table-column>
                <el-table-column
                    width="80"
                    prop="number"
                    label="販売数量">
                </el-table-column>
                <el-table-column
                    width="120px"
                    prop="stock_status"
                    label="在庫状況">
                </el-table-column>
                <el-table-column
                    width="200px"
                    label="操作">
                    <template slot-scope="scope">
                        <el-button
                            size="mini"
                            icon="el-icon-edit"
                            @click="handleClick(scope.row)">編集
                        </el-button>
                        <el-button
                            size="mini"
                            type="danger"
                            icon="el-icon-delete"
                            @click="handleDelete(scope.row)">删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                background
                :current-page="queryForm.page"
                :page-size="queryForm.limit"
                :layout="layout"
                :total="total"
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
            ></el-pagination>
            <product-add ref="productAdd" @fetch-data="queryData"></product-add>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                layout: 'total, sizes, prev, pager, next, jumper',
                queryForm: {
                    page: 1,
                    limit: 20
                },
                tableData: [],
                loading:false,
                total:0,
                height:0
            }
        },
        created(){

            this.height = document.body.clientHeight - 300

            this.fetchData();
        },
        mounted() {
            const that = this;
            window.onresize = function temp() {
                that.height = document.body.clientHeight - 300
            };
        },
        methods: {
            dateFormat(row, column, cellValue, index){
                return moment(cellValue).format('yyyy-MM-DD HH:mm')
            },
            numberFormat(row, column, cellValue) {
                return '¥' + this.$number_format(cellValue)
            },
            handleClick(row) {
                this.$refs['productAdd'].showDialog(row);
            },
            handleDelete(row) {
                let _this =this;
                this.$confirm('商品を削除してよろしいでしょうか？', 'ヒント', {
                    confirmButtonText: '確認',
                    cancelButtonText: 'キャンセル',
                    type: 'warning'
                }).then(() => {
                    axios.post('/restrant/product/delete', {'id':row.id})
                        .then(function (response) {
                            _this.loading = false
                            const {status, message, data} = response.data;
                            if(status === 200){
                                _this.$message({
                                    type: 'success',
                                    message: message
                                });
                                _this.fetchData();
                            }else{
                                _this.$message.error(message);
                            }
                        })
                        .catch(function (error) {
                            _this.loading = false
                            _this.$message.error('削除失敗しました');
                        });
                }).catch(() => {

                });
            },
            handleSizeChange(val) {
                this.queryForm.limit = val
                this.fetchData()
            },
            handleCurrentChange(val) {
                this.queryForm.page = val
                this.fetchData()
            },
            queryData() {
                this.queryForm.page = 1
                this.fetchData()
            },
            fetchData(){
                let _this = this;
                _this.loading = true
                axios.post('/restrant/product/pager', _this.queryForm)
                    .then(function (response) {
                        _this.loading = false
                        const {status, message, data} = response.data;
                        if(status === 200){
                            _this.total = data.total;
                            _this.tableData = data.data;
                        }
                    })
                    .catch(function (error) {
                        _this.loading = false
                        _this.$message.error('取得失敗しました');
                    });
            }
        },
    }
</script>
