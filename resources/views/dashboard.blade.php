<x-app-layout>
    <x-slot name="header">
{{--        <h2 class="font-semibold text-xl text-gray-800 leading-tight">--}}
{{--            {{ __('マイページ') }}--}}
{{--        </h2>--}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-white border-b border-gray-200">

                    <div class="card text-center">
                        <div class="card-header">
                            <ul class="nav nav-pills card-header-tabs" id="myTab" role="tablist">
                                <li class="nav-item ">
                                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
                                       aria-controls="home" aria-selected="true">全て</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pay-tab" data-toggle="tab" href="#pay" role="tab"
                                       aria-controls="profile" aria-selected="false">支払い待ち</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body" style="padding: 10px 20px">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="all" role="tabpanel"
                                     aria-labelledby="home-tab">
                                    @if(sizeof($orders) == 0)
                                        <div class="container py-3">
                                            注文データがありません
                                        </div>
                                    @else
                                        @foreach($orders as $key => $order)
                                            <div class="card" style="margin-top: 30px;border-color: #848486;box-shadow: 3px 2px 8px #BBBBBB;">
                                                <div class="card-header order-bar">
                                                    <div class="info text-left">
                                                        注文番号：{{$order->order_sn}}
                                                    </div>
                                                    <div class="status text-right">
                                                        @if($order->pay_status == 0 && $order->status == 0)
                                                            支払い待ち
                                                        @endif
                                                        @if($order->pay_status == 1 && $order->status == 1)
                                                            注文完了
                                                        @endif
                                                        @if($order->status == 2)
                                                            キャンセル
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-body order-body">
                                                    @foreach($order->products as $product)
                                                        <div class="media cart-media">
                                                            <img src="/uploads/{{$product->picture_path}}" style="width: 64px;height: 64px" />
                                                            <div class="media-body text-left">
                                                                <h5 class="mt-0">{{$product->name}}</h5>
                                                                <p class="mb-0">内容量:{{$product->column}}</p>
                                                            </div>
                                                            <div class="goods-price">¥{{number_format($product->pivot->product_price)}} × {{$product->pivot->product_number}}</div>
                                                        </div>
                                                    @endforeach
                                                    @if($order->status == 1)
                                                            <div class="card-body　media text-left p-0">
                                                                <div class="card-header">
                                                                    注文詳細
                                                                </div>
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item"><b>{{$order->restrant->name}}</b> <br/> {{$order->restrant->tel}} <br/>{{$order->restrant->pref}}{{$order->restrant->city}}{{$order->restrant->street}}</li>
                                                                    <li class="list-group-item">受取人：{{$order->consignee}}</li>
                                                                    @if($order->payment == 1)
                                                                    <li class="list-group-item">受取希望日：{{$order->receiveDate}}</li>
                                                                    <li class="list-group-item">受取時間帯：@if ($order->receiveTime == '')
                                                                            希望なし
                                                                        @endif @if ($order->receiveTime == '0812')
                                                                            午前中
                                                                        @endif @if ($order->receiveTime == '1416')
                                                                            14時～16時
                                                                        @endif @if ($order->receiveTime == '1618')
                                                                            16時～18時
                                                                        @endif @if ($order->receiveTime == '1820')
                                                                            18時～20時
                                                                        @endif @if ($order->receiveTime == '1921')
                                                                            19時～21時
                                                                        @endif</li>
                                                                    @else
                                                                        <li class="list-group-item">受け取る時間：{{$order->delivery_date}}</li>
                                                                    @endif
                                                                    <li class="list-group-item">電話番号：<a href="tel:{{$order->tel}}">{{$order->tel}}</a></li>
                                                                    <li class="list-group-item">メールアドレス：<a href="mailto:{{$email}}">{{$email}}</a></li>
                                                                    <li class="list-group-item">郵便番号：〒{{$order->post}}</li>
                                                                    <li class="list-group-item">都道府県：{{$order->pref}}</li>
                                                                    <li class="list-group-item">詳細住所：{{$order->address}}</li>
                                                                    @if($order->payment == 1)
                                                                        <li class="list-group-item">支払い方法：現地決済</li>
                                                                    @endif
                                                                    @if($order->payment == 2)
                                                                        <li class="list-group-item">支払い方法：郵送</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @endif
                                                </div>

                                                <div class="card-footer text-muted order-foot">
                                                    <div class="row order-buttons">
                                                        <div class="col-md-8 col-sm-12">
                                                            @if($order->pay_status == 1 && $order->status == 1)
                                                                手数料：<span class="goods-price">¥{{number_format($order->tip_fee)}}</span>　
                                                                配送料：<span class="goods-price">¥{{number_format($order->delivery_fee)}}</span>　
                                                                注文金額：<span class="goods-price">¥{{number_format($order->product_amount)}}</span>　
                                                                お支払い(税込み)：<span class="goods-price">¥{{number_format($order->order_amount)}}</span>　
                                                            @else
                                                                注文金額：<span class="goods-price">¥{{number_format($order->product_amount)}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 text-right">
                                                            @if($order->pay_status == 0 && $order->status == 0)
                                                                <form action="/payment" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="order_sn" value="{{$order->order_sn}}" />
                                                                    <input type="hidden" name="order_id" value="{{$order->id}}" />
                                                                    <input type="hidden" name="totalPrice" value="{{$order->order_amount}}" />
                                                                    <button type="button" class="btn btn-secondary btn-sm btn-cancel" data-id="{{$order->id}}">注文取消</button>
                                                                    <input type="submit" class="btn btn-danger btn-sm" value="レジに進む" />
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="pay" role="tabpanel" aria-labelledby="profile-tab">
                                    @if(sizeof($payOrders) == 0)
                                        <div class="container py-3">
                                            注文データがありません
                                        </div>
                                    @else
                                        @foreach($payOrders as $order)
                                            <div class="card" style="margin-top: 30px;border-color: #848486;box-shadow: 3px 2px 8px #BBBBBB;">
                                                <div class="card-header order-bar">
                                                    <div class="info text-left">
                                                        注文番号：{{$order->order_sn}}
                                                    </div>
                                                    <div class="status text-right">
                                                        @if($order->pay_status == 0 && $order->status == 0)
                                                            支払い待ち
                                                        @endif
                                                        @if($order->pay_status == 1 && $order->status == 1)
                                                            注文完了
                                                        @endif
                                                        @if($order->status == 2)
                                                            注文キャンセル
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-body order-body">
                                                    <div class="alert alert-light text-left" role="alert"><b>{{$order->restrant->name}}</b> <br/> {{$order->restrant->tel}}<br/> {{$order->restrant->pref}}{{$order->restrant->city}}{{$order->restrant->street}}</div>
                                                    @foreach($order->products as $product)
                                                        <div class="media cart-media">
                                                            <img src="/uploads/{{$product->picture_path}}" style="width: 64px;height: 64px" />
                                                            <div class="media-body text-left">
                                                                <h5 class="mt-0">{{$product->name}}</h5>
                                                                <p class="mb-0">内容量:{{$product->column}}</p>
                                                            </div>
                                                            <div class="goods-price">¥{{number_format($product->pivot->product_price)}} × {{$product->pivot->product_number}}</div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                                <div class="card-footer text-muted order-foot">
                                                    <div class="row order-buttons">
                                                        <div class="col-md-6 col-sm-12">
                                                            注文価格：<span class="goods-price">¥{{number_format($order->order_amount)}}</span>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 text-right">
                                                            @if($order->pay_status == 0 && $order->status == 0)
                                                                <form action="/payment" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="order_sn" value="{{$order->order_sn}}" />
                                                                    <input type="hidden" name="order_id" value="{{$order->id}}" />
                                                                    <input type="hidden" name="totalPrice" value="{{$order->order_amount}}" />
                                                                    <button type="button" class="btn btn-secondary btn-sm btn-cancel" data-id="{{$order->id}}">注文取消</button>
                                                                    <input type="submit" class="btn btn-danger btn-sm" value="レジに進む" />
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('modals')
        <script type="text/javascript">
            order.init();
        </script>
    @endpush
</x-app-layout>
