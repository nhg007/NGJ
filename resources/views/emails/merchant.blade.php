<b>お客様情報:</b>

<br/>

    お客様名前：{{$order->consignee}} <br/>
    電話：{{$order->tel}} <br/>
{{--    郵便番号：{{$order->post}} <br/>--}}
{{--    詳細住所：{{$order->address}} <br/>--}}
    備考：{{$order->remark}} <br/>
    受け取る時間：{{$order->delivery_date}} <br/>
──────────────────────────<br/>
<b>注文情報：</b><br/>
    注文番号：{{$order->order_sn}}<br/>
    注文価格：<span style="color: #b21f2d">¥{{number_format($order->order_amount)}}<br/></span>
    注文時間：{{$order->updated_at}}<br/>
    支払い方法：@if($order->payment == 1) 現地決済@elseクレジット@endif
<br/>
    @foreach($order->products as $product)
        商品名：{{$product->name}}<br/>
        内容量:{{$product->column}}<br/>
        商品価格：<span style="color: #b21f2d">¥{{number_format($product->pivot->product_price)}} × {{$product->pivot->product_number}}</span><br/>
    @endforeach
<br/>
──────────────────────────<br/>
〒192-0911<br/>
東京都八王子市打越町333-2TK北野ビル3階<br/>
(合)ワイルドライフ<br/>
ジビエマルシェ課<br/>
TEL   ：0426-33-9005<br/>
FAX  ：0426-32-8742<br/>
MAIL：order@gibier-marche.com<br/>
HP    ：http://gibier-marche.com<br/>
──────────────────────────<br/>
──────────────────────────<br/>
