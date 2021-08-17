{{$order->consignee}}さん！<br/>
ご注文ありがとうございます。<br/>
{{$order->updated_at}}の注文番号は：<b>{{$order->order_sn}} </b>です。<br/>
今後のお問い合わせの際には、この注文番号をご提示ください。<br/>
<br/>
<br/>
お客様名前：{{$order->consignee}} <br/>
電話：{{$order->tel}} <br/>
{{--郵便番号：{{$order->post}} <br/>--}}
{{--詳細住所：{{$order->address}} <br/>--}}
備考：{{$order->remark}} <br/>
受け取る時間：{{$order->delivery_date}} <br/>
──────────────────────────<br/>
<b>注文情報：</b><br/>
注文番号：{{$order->order_sn}}<br/>
お問い合わせの際には、この注文番号をご提示ください。<br />
手数料：<span style="color: #b21f2d">¥{{ number_format($order->tip_fee) }}</span><br />
配送料：<span style="color: #b21f2d">¥{{ number_format($order->delivery_fee) }}</span><br />
注文金額：<span style="color: #b21f2d">¥{{ number_format($order->product_amount) }}</span><br />
お支払い(税込み)：<span style="color: #b21f2d">¥{{ number_format($order->order_amount) }}</span><br />
注文時間：{{ $order->updated_at }}<br />
支払い方法：@if($order->payment == 1) 現地決済@else郵送@endif
<br/>
<b>内訳</b><br />
@foreach($order->products as $product)
    商品名：{{$product->name}}<br/>
    内容量:{{$product->column}}<br/>
    商品価格：<span style="color: #b21f2d">¥{{number_format($product->pivot->product_price)}} × {{$product->pivot->product_number}}</span><br/>
@endforeach
<br/>
@if($order->payment == 1)
    <b>配送先</b><br />
    郵便番号：〒{{ $order->post }} <br />
    都道府県：{{ $order->pref }} <br />
    住所：{{ $order->address }} <br />
    受取人：{{ $order->consignee }} <br />
    受取希望日：{{ $order->receiveDate }} <br />
    受取時間帯：@if ($order->receiveTime == '')
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
    @endif<br />
    電話番号：{{ $order->tel }} <br />
@else
    受け取る時間：{{ $order->delivery_date}} <br />

@endif
──────────────────────────<br/>
〒{{$restrant->post}}<br/>
{{$restrant->pref}}{{$restrant->city}}{{$restrant->street}}<br/>
{{$restrant->name}}<br/>
TEL   ：{{$restrant->tel}}<br/>
FAX  ：{{$restrant->fax}}<br/>
MAIL：{{$restrant->email}}<br/>
──────────────────────────<br/>
