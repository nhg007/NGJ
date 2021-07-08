<!-- 商品案内コンテンツ-->
<div class="row">
    @foreach($products as $item)
        <div class="col-md-6 p-3">
            <div class="card">
                <div class="card-header">{{$item->name}}</div>
                <div class="card-body">
                    <img src="/uploads/{{$item->picture_path}}" alt="{{$item->name}}" class="img-fluid">
                    <p class="card-title">
                        {{$item->description}}
                    </p>
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th>内容量</th>
                            <td>{{$item->column}}</td>
                        </tr>
                        <tr>
                            <th>価格</th>
                            <td>{{number_format($item->price)}}円（税別）</td>
                        </tr>
                        @if(isset($item->number))
                            <tr>
                                <th>残り件数</th>
                                <td>{{$item->number - $item->saledNumber}}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn btn-success cart-add" data-id="{{$item->id}}" data-rid="{{$restrantId}}"><i
                                class='fas fa-shopping-cart'></i>　カートに入れる
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


<script>

    $(document).ready(function () {
        productUtils.init();
    })
</script>

