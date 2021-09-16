<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レストラン管理</title>
    <!-- import CSS -->
    <link rel="stylesheet" href="//unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="{{url('/css/admin.css')}}">
</head>
<body>
<div id="app" v-cloak>
    <el-container>
        <el-aside width="200px" style="background-color: rgb(238, 241, 246)">
            <el-menu :default-openeds="['1','2','3']">
                <el-submenu index="1">
                    <template slot="title"><i class="el-icon-present"></i>商品管理</template>
                    <el-menu-item index="1-1">
                        <router-link to="/product">商品一覧</router-link>
                    </el-menu-item>
                </el-submenu>
                <el-submenu index="2">
                    <template slot="title"><i class="el-icon-shopping-cart-1"></i>注文管理</template>
                    <el-menu-item index="2-1">
                        <router-link to="/order">注文一覧</router-link>
                    </el-menu-item>
                </el-submenu>
                <el-submenu index="3">
                    <template slot="title"><i class="el-icon-setting"></i>システム管理</template>
                    <el-menu-item index="3-1">
                        <router-link to="/profile">店铺情報</router-link>
                    </el-menu-item>
                    <el-menu-item index="3-2">
                        <router-link to="/takeout">テイクアウト</router-link>
                    </el-menu-item>
                </el-submenu>
            </el-menu>
        </el-aside>
        <el-container class="right-side">
            <el-header style="text-align: right; font-size: 12px">
                <el-dropdown @command="handleCommand">
                    <span class="el-dropdown-link">
                    {{$user["name"]}}<i class="el-icon-arrow-down el-icon--right"></i>
                    </span>
                    <el-dropdown-menu slot="dropdown" >
                        <el-dropdown-item icon="el-icon-close" command="logout">ログアウト</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-header>
            <el-main>
                <router-view></router-view>
            </el-main>
        </el-container>
    </el-container>
</div>
</body>

<style>
    html body {
        position: relative;
        height: 100vh;
        padding: 0;
        margin: 0;
        font-family: Avenir,Helvetica,Arial,sans-serif;
        font-size: 14px;
        color: #2c3e50;
        background: #f6f8f9;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    #app > .el-container {
        border: 1px solid #eee;
        position: relative;
        width: 100%;
        height: 100%;
    }
    .el-header {
        background-color: #fff;
        color: #333;
        line-height: 60px;
        border-bottom: 1px solid #ccc;
    }

    .el-aside {
        color: #333;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 999;
        width: 256px;
        height: 100vh;
        overflow: hidden;
        background: #2f3447;
        box-shadow: 2px 0 6px rgb(0 21 41 / 35%);
        transition: width .3s;
    }
    .right-side{
        margin-left: 200px;
    }
    .el-menu-item a {
        color: #1b1e21;
        text-decoration: none;
    }
</style>
<!-- import Vue before Element -->
<script src="//unpkg.com/vue/dist/vue.js"></script>
<script src="//unpkg.com/vue-router/dist/vue-router.js"></script>
<script src="//unpkg.com/http-vue-loader"></script>
<!-- import JavaScript -->
<script src="//unpkg.com/element-ui"></script>
<script src="//unpkg.com/element-ui/lib/umd/locale/ja.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDK6GPBCxZEmYmCPckjSG4cjonOespvxE8&language=ja"></script>
<script src="{{mix('js/app.js')}}"></script>
<script>

    //多语言化
    ELEMENT.locale(ELEMENT.lang.ja);

    //商品列表最近
    let ProductComponent = httpVueLoader('{{url('/vue/product/list.vue')}}');
    let ProductAddComponent = httpVueLoader('{{url('/vue/product/add.vue')}}');
    let OrderComponent = httpVueLoader('{{url('/vue/order/list.vue')}}');
    let ProfileComponent = httpVueLoader('{{url('/vue/profile/index.vue')}}');
    let TakeoutComponent = httpVueLoader('{{url('/vue/takeout/index.vue')}}');
    let TakeoutItemComponent = httpVueLoader('{{url('/vue/takeout/component/item.vue')}}');

    //全局注册组件
    Vue.component('ProductAdd', ProductAddComponent)
    Vue.component('TakeoutItemComponent',TakeoutItemComponent)

    //格式化数字
    Vue.prototype.$number_format = function (number, decimals, dec_point, thousands_sep) {
        //decimals = 2;//这里默认设置保留两位小数，也可以注释这句采用传入的参数
        /*
       3     * 参数说明：
       4     * number：要格式化的数字
       5     * decimals：保留几位小数
       6     * dec_point：小数点符号
       7     * thousands_sep：千分位符号
       8     * */
        number = (number + '').replace(/[^0-9+-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.ceil(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        var re = /(-?\d+)(\d{3})/;
        while (re.test(s[0])) {
            s[0] = s[0].replace(re, "$1" + sep + "$2");
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    /* 全局Message */
    Vue.prototype.$baseMessage = (message, type) => {
        new Vue().$message({
            offset: 60,
            showClose: true,
            message: message,
            type: type,
            dangerouslyUseHTMLString: true,
            duration: 3000,
        })
    }

    const routes = [
        { path: '/product', component:ProductComponent},
        { path: '/order', component:OrderComponent},
        { path: '/profile',component:ProfileComponent },
        { path: '/takeout',component:TakeoutComponent },
        { path: '*', redirect: '/product' },
    ]
    const router = new VueRouter({
        routes
    })
    const app = new Vue({
        router,
        methods:{
            handleCommand:function (command) {
                this.$message('ログアウトしました');
                location.href="/restrant/logout";
            }
        }
    }).$mount('#app')
</script>
</html>
