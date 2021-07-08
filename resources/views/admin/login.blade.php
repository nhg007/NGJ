<!doctype html>
<html lang="ja">
<head>
    <title>ログイン</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link href="{{url('/css/signin.css')}}" rel="stylesheet">
</head>
<body class="text-center">
<div class="container" id="app">
    <form class="form-signin" v-on:submit.prevent>
        <!-- <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1> -->
        <h1 class="h3 mb-3 font-weight-normal">ログイン</h1>
        <!-- <label for="inputEmail" class="sr-only">Email address</label> -->
        <label for="inputEmail" class="sr-only">メールアドレス</label>
        <!-- <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus> -->
        <input type="email" id="inputEmail" class="form-control" placeholder="メールアドレス" required autofocus
               v-model="form.email">
        <!-- <label for="inputPassword" class="sr-only">Password</label> -->
        <label for="inputPassword" class="sr-only">パスワード</label>
        <!-- <input type="password" id="inputPassword" class="form-control" placeholder="Password" required> -->
        <input type="password" id="inputPassword" class="form-control" placeholder="パスワード" required
               v-model="form.password">
        <div class="checkbox mb-3">
            <label>
                <!-- <input type="checkbox" value="remember-me"> Remember me -->
                <input type="checkbox" value="remember-me"> 記憶する
            </label>
        </div>
        <!-- <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button> -->
        <button class="btn btn-lg btn-primary btn-block" type="submit" @click="submit"
                　v-loading.fullscreen.lock="fullscreenLoading">ログインする
        </button>
        <p class="mt-5 mb-3 text-muted">&copy; 2021-2022</p>
    </form>
</div>

</body>
<script src="{{ url('/js/vue.min.js') }}"></script>
<script src="{{mix('js/app.js')}}"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    new Vue({
        el: '#app',
        data: function () {
            return {
                form: {
                    email: '',
                    password: ''
                },
                fullscreenLoading: false
            }
        },
        methods: {
            submit() {
                let form = this.form;
                let _this = this;
                _this.fullscreenLoading = true
                // 为给定 ID 的 user 创建请求
                axios.post('/admin/login', form)
                    .then(function (response) {
                        _this.fullscreenLoading = false
                        const {status, message} = response.data;
                        if (status === 200) {
                            setTimeout(() => {
                                location.href = '/admin/index'
                            }, 500)
                        } else {
                            _this.$message.error(message);
                        }
                    })
                    .catch(function (error) {
                        _this.fullscreenLoading = false
                        _this.$message.error('ログイン失敗しました');
                    });
            }
        }
    })
</script>
</html>
