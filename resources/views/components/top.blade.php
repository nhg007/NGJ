<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm sticky-top" >
    <h5 class="my-0 mr-md-auto font-weight-normal"><a href="https://gibier-japon.com">GIBIER JAPON</a></h5>
    @include('components.search')
    @if(\Illuminate\Support\Facades\Auth::check())

        <a class="nav-link" href="/cart"><i
                class='fas fa-shopping-cart'></i> カート <span id="badge" class="badge badge-danger" style="display: none">0</span></a>

        <a class="btn btn-outline-success" href="/dashboard">マイページ</a>
    @else
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="btn btn-outline-secondary" href="/register">会員登録(無料)</a>
        </nav>
        <a class="btn btn-outline-success" href="/login">ログイン</a>
    @endif
</div>

<header id="header" class="py-4">
   <a href="https://gibier-japon.com"><img class="img-fluid" src="img/animal.jpg" alt="動物の画像"></a>
</header>
<div class="row p-3">
<div class="col-md-3"></div>
<div class="col-md-6"><a href="https://gibier-japon.com"><img class="img-fluid" src="img/logo.jpg" alt="Logo"></a></div>
<div class="col-md-3"></div>

</div>

<div class="row p-3">
<div class="col-md-3"></div>
<div class="col-md-6"><p class="oishiku">日本は美味しいジビエで溢れている！</p></div>
<div class="col-md-3"></div>
</div>
