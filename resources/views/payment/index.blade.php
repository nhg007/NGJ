<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お支払い') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <div class="card">
                    <div class="card-header">
                        注文情報
                    </div>
                    <div class="card-body">
                        <p>注文番号：{{$order_sn}}</p>
                        <p>お支払い金額：<span class="goods-price">¥{{number_format($totalPrice)}}</span></p>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        基本情報
                    </div>
                    <div class="card-body">
                        <form class="frmConsignee " method="POST" action="/paymentPost">
                            @csrf
                            <div class="form-group">
                                <label for="consignee">お名前 <span class="required">*</span></label>
                                <input type="text" class="form-control required" id="consignee" name="consignee"
                                       placeholder="お名前を入力してくだい">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">電話番号 <span class="required">*</span></label>
                                <input type="text" class="form-control required digits" id="tel" name="tel"
                                       placeholder="電話番号を入力してくだい">
                            </div>
                            <div class="form-group hidden">
                                <label for="exampleFormControlInput1">郵便番号 <span class="required">*</span></label>
                                <input type="text" class="form-control required digits" name="post" id="post"
                                       placeholder="郵便番号を入力してくだい">
                            </div>
                            <div class="form-group hidden">
                                <label for="exampleFormControlSelect2">詳細住所 <span class="required">*</span></label>
                                <input type="text" class="form-control required" name="address" id="address"
                                       placeholder="詳細住所を入力してくだい">
                            </div>
                            <div class="form-group">
                                <label for="remark">備考欄</label>
                                <textarea class="form-control" id="remark" name="remark" rows="3"
                                          placeholder="必要な場合のみご入力してくだい"></textarea>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$order_id}}">
                            <input type="hidden" name="payment" id="payment" />
                            <input type="hidden" name="takeoutId" id="takeoutId" />
                            <input type="hidden" name="delivery_date" id="delivery_date" />
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        お支払い方法
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="radio" id="chkPayment" name="payment" value="1" checked/>
                            <label for="chkPayment">現地決済</label>
                        </div>
                        <div class="form-group hidden">
                            <input type="radio" id="chkCredit" name="payment" value="2"/>
                            <label for="chkCredit">クレジット</label>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        テイクアウト来店時間帯
                    </div>
                    <div class="card-body">
                        @if(sizeof($takeoutTimes) == 0)
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
                                @foreach($takeoutTimes as $takeoutTime)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-group">
                                                <input type="radio" name="time" value="{{$takeoutTime->id}}" class="takeoutRule"/>
                                            </div>
                                        </th>
                                        <td>{{$takeoutTime->take_date}}</td>
                                        <td>{{$takeoutTime->start_time}}</td>
                                        <td>{{$takeoutTime->end_time}}</td>
                                        <td>{{$takeoutTime->number}}</td>
                                        <td style="width: 400px">
                                            <div class="takeOutTimes" style="position: relative">
                                                <input type="text" class="form-control text-left delivery_date"
                                                       disabled
                                                       data-take_date="{{$takeoutTime->take_date}}"
                                                       data-start_time="{{$takeoutTime->start_time}}"
                                                       data-end_time="{{$takeoutTime->end_time}}"
                                                       autocomplete="off" name="delivery_date" placeholder="货物を受け取る時間を入力してくだい">
                                                <span  class="error" for="delivery_date"></span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-white text-right deliveryCash sm:px-6">
                    <button type="button" id="btnConfirm"
                            class="inline-flex items-center px-4 py-2
                             bg-green-500 border border-transparent
                              rounded-md font-semibold text-xs
                               text-white uppercase tracking-widest
                                hover:bg-green-600 active:bg-green-700
                                focus:outline-none focus:border-green-800
                                focus:shadow-outline-green
                                disabled:opacity-25 transition ease-in-out duration-150" disabled="disabled">
                        注文確認
                    </button>
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-white text-right sm:px-6 credit hide">
                    <form id="frmCredit" METHOD="POST" ACTION="{{env('PAY_CREDIT_URL')}}" TARGET="_top">
                        <input TYPE="hidden" NAME="clientip" VALUE="{{env('IP_CODE')}}" />
                        <input TYPE="hidden" NAME="money" VALUE="{{$totalPrice}}" />
                        <input TYPE="hidden" NAME="telno" id="telno" VALUE="" />
                        <input TYPE="hidden" NAME="email" VALUE="{{$email}}" />
                        <input TYPE="hidden" NAME="success_url" VALUE="{{env('APP_URL')}}/paySuccess?orderId={{$order_id}}&payment=2" />
                        <input TYPE="hidden" NAME="success_str" VALUE="支払い成功しました" />
                        <input TYPE="hidden" NAME="failure_url" VALUE="{{env('APP_URL')}}/payFailed?orderId={{$order_id}}&payment=2" />
                        <input TYPE="hidden" NAME="failure_str" VALUE="支払い失敗しました" />
                        <button type="button" id="btnCredit"
                                class="inline-flex
                                 items-center
                                 px-4 py-2
                                 bg-green-500 border
                                 border-transparent
                                 rounded-md font-semibold
                                 text-xs text-white uppercase tracking-widest
                                 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-800 focus:shadow-outline-green
                                disabled:opacity-25 transition ease-in-out duration-150">
                            クレジット決済ページへ
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="paymentConfirm"
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
    </div>
    @push('modals')
        <script src="{{url('/js/jquery.validate.min.js')}}"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script src="/js/bootstrap-datetimepicker-ja.js"></script>
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

            $(document).ready(function () {
                payment.init();

                //$('#delivery_date').datetimepicker();
            })
        </script>
    @endpush
</x-app-layout>
