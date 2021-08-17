<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お支払い') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <form class="frmConsignee " method="POST" action="/paymentPost">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            商品のお届け
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="consignee" class="col-sm-2 col-form-label ">
                                    お届け先 <span class="required">*</span>
                                </label>
                                <select class="form-control col-sm-6 required" id="slReceiver" name="receiveType">
                                    <option value="登録住所" {{ $order->receive_type == '登録住所' ? 'selected' : '' }}>登録住所
                                    </option>
                                    <option value="その他" {{ $order->receive_type == 'その他' ? 'selected' : '' }}>その他
                                    </option>
                                </select>
                                <input id="userName" type="hidden" value="{{ $user->name }}"/>
                                <input id="userTelphone" type="hidden" value="{{ $user->telphone }}"/>
                                <input id="userPost" type="hidden" value="{{ $user->post }}"/>
                                <input id="userTodofuken" type="hidden" value="{{ $user->pref }}"/>
                                <input id="userAddress" type="hidden" value="{{ $user->address }}"/>
                            </div>
                            <div class="receiver-info {{ $order->receive_type == 'その他' ? '' : 'hidden' }}">
                                <div class="form-group row">
                                    <label for="consignee" class="col-sm-2 col-form-label">お名前 <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control col-sm-6  required" id="consignee"
                                           value="{{ $order->receive_type == 'その他' ? $order->consignee : '' }}"
                                           name="consignee" placeholder="お名前を入力してくだい">
                                </div>
                                <div class="form-group row">
                                    <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">電話番号 <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control col-sm-6 required digits" id="tel" name="tel"
                                           value="{{ $order->receive_type == 'その他' ? $order->tel : '' }}"
                                           placeholder="電話番号を入力してくだい">
                                </div>
                                <div class="form-group row">
                                    <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">郵便番号 <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control  col-sm-3 required" name="post" id="post"
                                           value="{{ $order->receive_type == 'その他' ? $order->post : '' }}"
                                           placeholder="郵便番号を入力してくだい"/>
                                    <input id="zipSearch" class="inline-flex
                                        items-center
                                        bg-green-500 border
                                        border-transparent
                                        rounded-md font-semibold
                                        text-xs text-white uppercase tracking-widest
                                        hover:bg-green-600 active:bg-green-700
                                        focus:outline-none focus:border-green-800
                                        focus:shadow-outline-green
                                        disabled:opacity-25 transition ease-in-out duration-150"
                                           style="margin-left: 20px;text-align: center" value="郵便番号検索"/>

                                </div>
                                <div class="form-group row">
                                    <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">都道府県 <span
                                            class="required">*</span></label>
                                    <select class="form-control col-sm-3 required" name="pref" id="pref"
                                            data-value="{{ $order->receive_type == 'その他' ? $order->pref : '' }}">

                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label for="exampleFormControlSelect2" class="col-sm-2 col-form-label">詳細住所 <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control required col-sm-6" name="address"
                                           value="{{ $order->receive_type == 'その他' ? $order->address : '' }}"
                                           id="address" placeholder="詳細住所を入力してくだい">
                                </div>
                            </div>
                            <div class="form-group row delivery-baseinfo">
                                <label for="remark" class="col-sm-2 col-form-label">受取希望日</label>
                                <input class="form-control col-sm-6 required" id="receiveDate" name="receiveDate"
                                       value="{{ $order->receiveDate }}" placeholder="受取希望日を入力してください"></input>
                            </div>
                            <div class="form-group row delivery-baseinfo">
                                <label for="originalTimeId" class="col-sm-2 col-form-label">受取時間帯</label>
                                <select class="form-control col-sm-6" id="receiveTime" name="receiveTime">
                                    <option value="">希望なし</option>
                                    <option value="0812" {{ $order->receiveTime == '0812' ? 'selected' : '' }}>午前中
                                    </option>
                                    <option value="1416" {{ $order->receiveTime == '1416' ? 'selected' : '' }}>
                                        14時～16時
                                    </option>
                                    <option value="1618" {{ $order->receiveTime == '1618' ? 'selected' : '' }}>
                                        16時～18時
                                    </option>
                                    <option value="1820" {{ $order->receiveTime == '1820' ? 'selected' : '' }}>
                                        18時～20時
                                    </option>
                                    <option value="1921" {{ $order->receiveTime == '1921' ? 'selected' : '' }}>
                                        19時～21時
                                    </option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="remark" class="col-sm-2 col-form-label">備考欄</label>
                                <textarea class="form-control col-sm-6" id="remark" name="remark" rows="5"
                                          placeholder="必要な場合のみご入力してくだい">{{ $order->remark }}</textarea>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{ $order->id }}">
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            お支払い方法
                        </div>
                        <div class="card-body">
                            @if($restrant->paymentType == 'イートイン' || $restrant->paymentType == '郵送')
                                <div class="form-group">
                                    <input type="radio" id="chkPayment" name="payment" value="1"/>
                                    <label for="chkPayment">代引き 決済手数料：324円</label>
                                </div>
                            @endif
                            @if($restrant->paymentType == 'イートイン' || $restrant->paymentType == '現地決済')
                                <div class="form-group">
                                    <input type="radio" id="chkTakeout" name="payment" value="2"/>
                                    <label for="chkTakeout">現地決済</label>
                                </div>
                            @endif
                                <label id="payment-error" class="error" for="payment">お支払い方法を選択してください。</label>
                        </div>
                    </div>
                    <div class="card mt-3" id="divTakeoutTimes" style="display: none">
                        <div class="card-header">
                            テイクアウト来店時間帯
                        </div>
                        <div class="card-body">
                            @if (sizeof($takeoutTimes) == 0)
                                <div class="container py-3 text-center">
                                    来店時間帯設定していない
                                </div>
                            @else
                                <table class="table table-borderless">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">日付</th>
                                        <th scope="col">開始時間</th>
                                        <th scope="col">終了時間</th>
                                        <th scope="col">残り席</th>
                                        <th scope="col">受け取る時間</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($takeoutTimes as $takeoutTime)
                                        <tr>
                                            <th scope="row">
                                                <div class="form-group">
                                                    <input type="radio" name="takeoutId"
                                                           value="{{ $takeoutTime->id }}" class="takeoutRule"/>
                                                </div>
                                            </th>
                                            <td>{{ $takeoutTime->take_date }}</td>
                                            <td>{{ $takeoutTime->start_time }}</td>
                                            <td>{{ $takeoutTime->end_time }}</td>
                                            <td>{{ $takeoutTime->number }}</td>
                                            <td style="width: 400px">
                                                <div class="takeOutTimes" style="position: relative">
                                                    <input type="text" class="form-control text-left delivery_date"
                                                           disabled data-take_date="{{ $takeoutTime->take_date }}"
                                                           data-start_time="{{ $takeoutTime->start_time }}"
                                                           data-end_time="{{ $takeoutTime->end_time }}"
                                                           autocomplete="off" name="delivery_date"
                                                           placeholder="货物を受け取る時間を入力してくだい">
                                                    <span class="error" for="delivery_date"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="card mt-3">
                    <div class="card-header">
                        注文番号:　{{ $order->order_sn }}
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach ($order->products as $product)
                                <li class="media my-3 py-3 px-2">
                                    <img src="/uploads/{{ $product->picture_path }}">
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-1">{{ $product->name }}</h5>
                                        <p class="mb-1">内容量 : {{ $product->column }}</p>
                                        <p class="mb-0" data-keeping="{{ $product->keeping }}">保存方式 :
                                            {{ $product->keeping }}</p>
                                        <p>
                                            <span
                                                class="goods-price">¥{{ number_format($product->pivot->product_price) }}</span>
                                            × {{ $product->pivot->product_number }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="list-unstyled order-paymentAmount">
                            <li class="deliveryGroup">送料について　<span class="badge badge-danger">送料は、全国一律９７２円です。（北海道・沖縄は１４０４円）</span></li>
                            <li class="deliveryGroup">配送料:　<span id="fee" class="goods-price">なし</span></li>
                            <li id="tip" class="hide deliveryGroup">手数料:　<span id="tipfee" class="goods-price">なし</span></li>
                            <li>注文金額:　<span class="goods-price">¥{{ number_format($order->product_amount) }}</span></li>
                            <li>消費税:　10%</li>
                            <li>お支払い金額:　<span id="totalPay" class="goods-price"></span></li>
                        </ul>
                        <input type="hidden" id="productAmout" value="{{ $order->product_amount }}"/>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-white text-right deliveryCash sm:px-6">
                    <button type="button" id="btnConfirm" class="inline-flex items-center px-4 py-2
                             bg-green-500 border border-transparent
                              rounded-md font-semibold text-xs
                               text-white uppercase tracking-widest
                                hover:bg-green-600 active:bg-green-700
                                focus:outline-none focus:border-green-800
                                focus:shadow-outline-green
                                disabled:opacity-25 transition ease-in-out duration-150">
                        注文確認
                    </button>
                </div>
            </div>

        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="paymentConfirm" aria-labelledby="gridSystemModalLabel">
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

        <div class="modal fade" id="orderConfirmModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">注文確認</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="card mt-3">
                                <div class="card-header">
                                    注文番号:　{{ $order->order_sn }}
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        @foreach ($order->products as $product)
                                            <li class="media my-3 py-3 px-2">
                                                <img src="/uploads/{{ $product->picture_path }}">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">{{ $product->name }}</h5>
                                                    <p class="mb-1">内容量 : {{ $product->column }}</p>
                                                    <p class="mb-0" data-keeping="{{ $product->keeping }}">保存方式 :
                                                        {{ $product->keeping }}</p>
                                                    <p>
                                                        <span
                                                            class="goods-price">¥{{ number_format($product->pivot->product_price) }}</span>
                                                        × {{ $product->pivot->product_number }}
                                                    </p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header">
                                    商品のお届け
                                </div>
                                <div class="card-body">
                                    <div class="userRegister hide">
                                        <div class="form-group row">
                                            <label class="col-5 col-sm-2 col-form-label">お名前 </label>
                                            <span class="col-form-label">{{ $user->name }}</span>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-5 col-sm-2 col-form-label">電話番号</label>
                                            <span class="col-form-label"
                                                  id="lblTelphone">{{ $user->telphone }}</span>
                                        </div>
                                        <div class="form-group row">
                                            <label for="spost" class="col-5 col-sm-2 col-form-label">郵便番号 </label>
                                            <span class="col-form-label" id="lblPost">{{ $user->post }}</span>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-5 col-sm-2 col-form-label">都道府県 </label>
                                            <span class="col-form-label"
                                                  id="lblTodofuken">{{ $user->pref }}</span>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-5 col-sm-2 col-form-label">詳細住所 </label>
                                            <span class="col-form-label" id="lblAddress">{{ $user->address }}</span>
                                        </div>
                                    </div>
                                    <div class="basicInfo hide">
                                        <div class="form-group row">
                                            <label for="consignee" class="col-5 col-sm-2 col-form-label">お名前 </label>
                                            <span class="col-form-label" id="spconsignee"></span>
                                        </div>
                                        <div class="form-group row">
                                            <label for="sptel" class="col-5 col-sm-2 col-form-label">電話番号</label>
                                            <span class="col-form-label" id="sptel"></span>
                                        </div>
                                        <div class="form-group row">
                                            <label for="spost" class="col-5 col-sm-2 col-form-label">郵便番号 </label>
                                            <span class="col-form-label" id="spost"></span>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exampleFormControlInput1"
                                                   class="col-5 col-sm-2 col-form-label">都道府県 </label>
                                            <span class="col-form-label" id="sptodofuken"></span>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exampleFormControlSelect2"
                                                   class="col-5 col-sm-2 col-form-label">詳細住所 </label>
                                            <span class="col-form-label" id="spaddress"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row confirm-delivery">
                                        <label for="receiveDate" class="col-5 col-sm-2 col-form-label ">受取希望日</label>
                                        <span class="col-form-label" id="spreceiveDate"></span>
                                    </div>
                                    <div class="form-group row confirm-delivery">
                                        <label for="originalTimeId" class="col-5 col-sm-2 col-form-label">受取時間帯</label>
                                        <span class="col-form-label" id="spreceiveTime"></span>
                                    </div>
                                    <div class="form-group row confirm-takeout">
                                        <label for="originalTimeId" class="col-5 col-sm-2 col-form-label">受け取る時間</label>
                                        <span class="col-form-label" id="takeoutTime"></span>
                                    </div>
                                    <div class="form-group row">
                                        <label for="remark" class="col-5 col-sm-2 col-form-label">備考欄</label>
                                        <pre class="col-form-label" id="spremark"></pre>
                                    </div>
                                    <div class="form-group row">
                                        <label for="payType" class="col-5 col-sm-2 col-form-label">支払い方法</label>
                                        <span class="col-form-label" id="spayType"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    合計
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled" id="payment-result"></ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">閉じる</button>
                        <button type="button" id="btnOrderInfoSend" class="inline-flex items-center px-4 py-2
                  bg-green-500 border border-transparent
                   rounded-md font-semibold text-xs
                    text-white uppercase tracking-widest
                     hover:bg-green-600 active:bg-green-700
                     focus:outline-none focus:border-green-800
                     focus:shadow-outline-green
                     disabled:opacity-25 transition ease-in-out duration-150">注文確定
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">プロフィール</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        プロフィール情報が不完全ですので、プロフィール情報を完備してください
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">注文続け</button>
                        <button type="button" class="btn btn-primary"
                                onclick="location.href='/user/profile'">プロフィールへ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('modals')
        <script src="{{ url('/js/jquery.validate.min.js') }}"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script src="{{ url('/js/jquery.validate.min.js') }}"></script>
        <script src="{{ url('/js/ajaxzip3.js') }}"></script>
        <script type="text/javascript">
            $.extend($.validator.messages, {
                required: "このフィールドは必須です。",
                remote: "このフィールドを修正してください。",
                email: "有効なEメールアドレスを入力してください。",
                url: "有効なURLを入力してください。",
                date: "有効な日付を入力してください。",
                dateISO: "有効な日付（ISO）を入力してください。",
                number: "有効な数字を入力してください。",
                digits: "数字のみを入力してください。",
                creditcard: "有効なクレジットカード番号を入力してください。",
                equalTo: "同じ値をもう一度入力してください。",
                extension: "有効な拡張子を含む値を入力してください。",
                maxlength: $.validator.format("{0} 文字以内で入力してください。"),
                minlength: $.validator.format("{0} 文字以上で入力してください。"),
                rangelength: $.validator.format("{0} 文字から {1} 文字までの値を入力してください。"),
                range: $.validator.format("{0} から {1} までの値を入力してください。"),
                step: $.validator.format("{0} の倍数を入力してください。"),
                max: $.validator.format("{0} 以下の値を入力してください。"),
                min: $.validator.format("{0} 以上の値を入力してください。")
            });

            moment.updateLocale('fr', {
                weekdays: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'],
                weekdaysShort: ['日', '月', '火', '水', '木', '金', '土', '日'],
                weekdaysMin: ['日', '月', '火', '水', '木', '金', '土', '日'],
                months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
            })
            moment.locale(window.navigator.userLanguage || window.navigator.language)

            $(document).ready(function () {
                payment.init();

                //$('#delivery_date').datetimepicker();
            })
        </script>
    @endpush
</x-app-layout>
