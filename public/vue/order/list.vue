<template>
    <div class="product-container">

        <div class="tools">
            <el-row :gutter="20">
                <el-col :span="4">
                    <el-input
                        placeholder="注文番号を入力してくだい"
                        prefix-icon="el-icon-search"
                        v-model="queryForm.keyword">
                    </el-input>
                </el-col>
                <el-col :span="8">
                    <el-button type="primary" icon="el-icon-search" @click="queryData">検索</el-button>
                </el-col>
            </el-row>

        </div>
        <div class="app-main-container">
            <el-table
                v-loading="loading"
                :data="tableData"
                border
                stripe
                :height="height"
                style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="props">
                        <el-card class="box-card">
                            <div slot="header" class="clearfix">
                                <span>注文詳細</span>
                            </div>
                            <div v-for="(item) in props.row.products" :key="item.id" class="text item">
                                <div class="media cart-media">
                                    <img v-if="item.picture_path" :src="'/uploads/' + item.picture_path">
                                    <div class="media-body text-left">
                                        <h5 class="mt-0">{{item.name}}</h5>
                                        <p class="mb-0">内容量:{{item.column}}</p>
                                    </div>
                                    <div class="goods-price">
                                        {{ item.pivot.product_price | numberFormat}} × {{item.pivot.product_number }}
                                    </div>
                                </div>
                            </div>
                            <div v-if="props.row.status == 1">
                                <div class="text item">
                                    電話番号：{{props.row.tel}}
                                </div>
                                <div class="text item">
                                    受け取る時間：{{props.row.delivery_date}}
                                </div>
                                <div class="text item">
                                    備考欄：{{props.row.remark}}
                                </div>
<!--                                <div class="text item">-->
<!--                                    郵便番号：〒{{props.row.post}}-->
<!--                                </div>-->
<!--                                <div class="text item">-->
<!--                                    詳細住所：{{props.row.address}}-->
<!--                                </div>-->
                                <div v-if="props.row.payment == 1" class="text item">
                                    支払い方法：現地決済
                                </div>
                                <div v-if="props.row.payment == 2" class="text item">
                                    支払い方法：クレジットカード
                                </div>
                            </div>
                        </el-card>
                    </template>
                </el-table-column>
                <el-table-column
                    :formatter="dateFormat"
                    width="140"
                    prop="updated_at"
                    label="注文日付">
                </el-table-column>
                <el-table-column
                    width="180"
                    prop="order_sn"
                    label="注文番号">
                </el-table-column>
                <el-table-column
                    width="120"
                    prop="order_amount"
                    :formatter="numberFormat"
                    label="注文価格">
                </el-table-column>
                <el-table-column
                    width="120"
                    prop="pay_status"
                    label="支払い状態">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.pay_status == 0"
                                type="danger"
                                disable-transitions>未払い
                        </el-tag>
                        <el-tag v-if="scope.row.pay_status == 1"
                                type="success"
                                disable-transitions>注文確定
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="status"
                    width="130"
                    label="注文状態">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.status == 1"
                                type="success"
                                disable-transitions>正常
                        </el-tag>
                        <el-tag v-if="scope.row.status == 2"
                                type="danger"
                                disable-transitions>キャンセル
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                    width="80"
                    prop="user.name"
                    label="会员名">
                </el-table-column>
                <el-table-column
                    prop="user.email"
                    label="Eメール">
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
                    keyword:'',
                    page: 1,
                    limit: 20,
                },
                tableData: [],
                loading: false,
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
        filters:{
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
            fetchData() {
                let _this = this;
                _this.loading = true
                axios.post('/restrant/order/pager', _this.queryForm)
                    .then(function (response) {
                        _this.loading = false
                        const {status, message, data} = response.data;
                        if (status === 200) {
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
<style>

    .media {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .media img {
        width: 50px;
        height: 50px;
        border: 1px solid #ccc;
        margin-right: 10px;
    }
    .media h5{
        margin: 0;
    }
    .media .media-body {
        margin-right: 10px;
    }
    .media .mb-0 {
        margin-bottom: 5px;
    }
    .goods-price {
        color: #b21f2d;
    }
    .box-card .text {
        padding: 3px;
    }
</style>
