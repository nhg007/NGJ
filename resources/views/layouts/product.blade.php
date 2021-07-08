@extends('layouts.front')

@section('css')
    <link href="css/higuma.reset.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="css/higuma.style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
@endsection

@section('content')
    <div class="back">
        <main id="contents" data-category="@yield('category')">
            <section class="section" id="works">
                <div class="inner">
                    <div class="section-title-block">
                        <h2 class="section-title">熊掌</h2>
                        <div class="section-desc">
                            <p class="text">
                                【一期一会の食材を選ぶ贅沢】</p>
                            <p>&nbsp;</p>
                            <p class="how_text-2"><span style="color:#ffa500; font-size:14px;">◆</span>おひとり250ｇ（記載重量）が目安です。<br><span
                                    style="color:#ffa500; font-size:14px;">◆</span>調理工程で肉球と骨を除くため、調理後の重量は、記載重量より約50％減ります。<br><span
                                    style="color:#ffa500; font-size:14px;">◆</span>表示金額は、調理を含めた金額です。（税別）<br><span
                                    style="color:#ffa500; font-size:14px;">◆</span>味付け・盛り付けは、提携料理店により異なります。<br>　詳しくは、提携料理店のご紹介ページをご覧ください。<br>
                                <span style="color:#ffa500; font-size:14px;">◆</span>熊掌のご購入を希望される料理店様は下記までご連絡くださいませ。<br>　熊乃家　by
                                ジビエマルシェ メールアドレス：<a href="mailto:order@kuma-no-te.com">order@kuma-no-te.com</a><br>　電話番号：0426-32-8746　営業時間：10：00～17：00（日祝除く）
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center loading">
                        <div class="spinner-grow" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <div class="container-fluid hidden">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center loading hidden">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="tab" role="tabpanel">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="nav-item active">
                                            <a href="#Section1" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-paw"></i><span>ロース</span></a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-paw"></i><span>バラ</span></a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#Section3" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-paw"></i><span>もも</span></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content tabs">
                                        <div role="tabpanel" class="tab-pane fade show active" id="Section1">
                                            <div class="d-flex justify-content-between flex-wrap"></div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="Section2">
                                            <div class="d-flex justify-content-between flex-wrap" ></div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="Section3">
                                            <div class="d-flex justify-content-between flex-wrap" ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/art-template@4.13.2/lib/template-web.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
    <script id="tpl_product" type="text/html">
        <% if (list.length == 0) { %>
        <div class="card ">
            <div class="card-body">
                データがありません
            </div>
        </div>
        <% }else{ %>
        <% for(var i = 0; i < list.length; i++){ %>
        <div class="card ">
            <a href="/uploads/<%=list[i].picture_path%>" data-lightbox="abc" data-title="<%=list[i].name%>" class="card-image">
                <img src="/uploads/<%=list[i].picture_path%>" class="img" alt="">
            </a>
            <div class="card-body">
                <h5 class="card-title"><%=list[i].name%></h5>
                <p class="card-text"><%=list[i].description%></p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">種類 : <%=list[i].category%> </li>
                <li class="list-group-item">部位 : <%=list[i].part%></li>
                <li class="list-group-item">脂 : <%=list[i].fat%> %</li>
                <li class="list-group-item">価格: <%= $imports.toThousands(list[i].price)%>円　<span class="<%= list[i].stock_status %>"><%=list[i].stock_status%></span></li>
            </ul>
            <div class="card-body">
                <a class="button cart-add" href="javascript:;" data-id="<%=list[i].id%>"> <i class="fa fa-shopping-cart"></i> カートに入れる</a>
                <a class="button" href="#"> <i class="fa fa-credit-card"></i> 今すぐ買う</a>
            </div>
        </div>
        <% } %>
        <% } %>
    </script>
    <script>
        $(document).ready(function () {
            productUitls.init();
        })
    </script>
    </script>
@endpush
