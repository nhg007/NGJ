<x-app-layout>

    @push('css')
        <style>
            .car-goods-info .desc {
                display: -webkit-box;
                overflow: hidden;
                white-space: normal !important;
                text-overflow: ellipsis;
                word-wrap: break-word;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical
            }
        </style>
    @endpush

    <x-slot name="header">
{{--        <h2 class="font-semibold text-xl text-gray-800 leading-tight">--}}
{{--            {{ __('カート') }}--}}
{{--        </h2>--}}
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="shopping-car-container">
                    <div class="car-headers-menu">
                        <div class="row">
                            <div class="" style="display: none">
                                <label>
                                    <input type="checkbox" id="check-goods-all"/>
                                </label>
                            </div>
                            <div class="col-7 col-md-5 car-menu">商品情報</div>
                            <div class="col-1 col-md-1 car-menu mobile-hide">単価</div>
                            <div class="col-5 col-md-2 car-menu">数量</div>
                            <div class="col-4 col-md-1 car-menu mobile-hide">金额</div>
                            <div class="col-1 col-md-2 car-menu mobile-hide">操作</div>
                        </div>
                    </div>
                    <div class="goods-content">
                        <!--goods display-->
                        @if(sizeof($restrantCarts) == 0)
                            <div class="container py-3 text-center">
                                カートの中身は空です
                            </div>
                        @else
                            @foreach($restrantCarts as $restrantCart)
                                <div class="card" style="margin-bottom: 20px;">
                                    <div class="card-header">
                                        {{$restrantCart["restrant_name"]}}
                                    </div>
                                    @foreach($restrantCart["restrant_carts"] as $cart)
                                        <div class="card-body">
                                            <div class="goods-item">
                                                <div class="card card-default">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="" style="display: none">
                                                                <label>
                                                                    <input type="checkbox" class="goods-list-item"
                                                                           data-id="{{$cart->id}}"
                                                                           data-restrantId="{{$restrantCart["restrant_id"]}}"
                                                                           data-price="{{$cart->price}}" checked>
                                                                </label>
                                                            </div>
                                                            <div
                                                                class="col-6 col-md-5 car-goods-info goods-image-column">
                                                                <img class="goods-image"
                                                                     src="/uploads/{{$cart->product->picture_path}}"
                                                                     style="width: 100px; height: 100px;">
                                                                <div class="goods-des">
                                                                    <h3 class="pro_name">{{$cart->product->name}} </h3>
                                                                    <div class="mobile-show goods-price hide">
                                                                        ￥{{number_format($cart->price)}}</div>
                                                                    <div
                                                                        class="desc mobile-hide">{{$cart->product->description}}</div>
                                                                    <div><a href="#"
                                                                            class="badge badge-success">{{$cart->product->column}}</a>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-1 col-md-1 car-goods-info goods-price mobile-hide">
                                                                <span>￥</span>
                                                                <span
                                                                    class="single-price">{{number_format($cart->price)}}</span>
                                                            </div>
                                                            <div class="col-5 col-md-2 car-goods-info goods-counts">
                                                                <div class="input-group">
                                                                    <div class="input-group-btn">
                                                                        <button type="button"
                                                                                class="btn btn-default car-decrease"
                                                                                data-id="{{$cart->id}}">-
                                                                        </button>
                                                                    </div>
                                                                    <input type="text" class="form-control goods-count"
                                                                           data-id="{{$cart->id}}"
                                                                           value="{{$cart->number}}">
                                                                    <div class="input-group-btn">
                                                                        <button type="button"
                                                                                class="btn btn-default car-add"
                                                                                data-id="{{$cart->id}}">+
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-4 col-md-1 car-goods-info goods-money-count goods-price mobile-hide">
                                                                <span>￥</span><span
                                                                    class="single-total">{{number_format($cart->number * $cart->price)}}  </span>
                                                            </div>
                                                            <div
                                                                class="col-1 col-md-2 car-goods-info goods-operate mobile-hide">
                                                                <button type="button" class="btn btn-danger item-delete"
                                                                        data-toggle="modal" data-id="{{$cart->id}}"
                                                                        data-target="#deleteItemTip"><i
                                                                        class="fa fa-trash-o"></i>
                                                                    删除
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @endforeach
                        @endif
                    </div>
                    <div class="card card-default">
                        <div class="card-body bottom-menu-include">
                            <form action="/cartConfirm" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-4 col-md-4 check-all-bottom">
                                        @if(sizeof($restrantCarts) == 0)
                                            <button class="btn btn-danger delete-all" disabled><i
                                                    class="fa fa-trash-o"></i> 删除
                                            </button>
                                        @else
                                            <button class="btn btn-danger delete-all"><i class="fa fa-trash-o"></i> 删除
                                            </button>
                                        @endif
                                    </div>
                                    <div class="col-md-1 bottom-menu mobile-hide">

                                    </div>
                                    <div class="col-md-1 bottom-men mobile-hide">

                                    </div>
                                    <div class="col-md-2 bottom-menu mobile-hide">
                                        <span>数量 <span class="selectGoodsCount">0</span> 件</span>
                                    </div>
                                    <div class="col-md-2 bottom-menu goods-price mobile-hide">
                                        合計：<span>￥</span><span class="selectGoodsMoney">0</span>
                                    </div>

                                    <div class="col-8 col-md-2 bottom-menu">
                                        <div class="hide mobile-show mr-2">
                                            合計：<span class="goods-price">￥</span><span
                                                class="selectGoodsMoney goods-price">0</span>
                                        </div>
                                        <input type="hidden" id="hdCartIds" name="cartIds"/>
                                        <input type="hidden" id="hdRestrantId" name="restrantId"/>
                                        <button type="button" id="btnOrder" disabled="disabled"
                                                class="btn btn-secondary"><i class="fa fa-credit-card"></i> 注文へ
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--删除确认弹框-->
                    <div class="modal fade" tabindex="-1" role="dialog" id="deleteItemTip"
                         aria-labelledby="gridSystemModalLabel">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="gridSystemModalLabel">ヒント</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    商品を削除してもよろしいでしょうか？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                                    <button type="button" class="btn btn-primary deleteSure">確定</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--是否勾选商品提示-->
                    <div class="modal fade" tabindex="-1" role="dialog" id="selectItemTip"
                         aria-labelledby="gridSystemModalLabel">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="gridSystemModalLabel">ヒント</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    削除する商品を選んでください！
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">確定</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--是否勾选商品提示-->
                    <div class="modal fade" tabindex="-1" role="dialog" id="deleteMultyTip"
                         aria-labelledby="gridSystemModalLabel">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="gridSystemModalLabel">ヒント</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    商品を削除してもよろしいでしょうか？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                                    <button type="button" class="btn btn-primary deleteMultySure">確認</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="cartUpdate"
             aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border: 0;background-color: transparent">
                    <div class="d-flex justify-content-center loading">
                        <div class="spinner-grow" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal"  id="modal1" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">メッセージ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>同じ店舗の商品を選んでご注文してください。</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('modals')
        <script type="text/javascript">
            cart.init();
        </script>
    @endpush
</x-app-layout>

