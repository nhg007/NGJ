<x-app-layout>
    <x-slot name="header">
{{--        <h2 class="font-semibold text-xl text-gray-800 leading-tight">--}}
{{--            {{ __('決済失敗') }}--}}
{{--        </h2>--}}
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">

                @if(isset($order))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">決済失敗しました</h4>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            注文情報
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">注文番号：{{$order->order_sn}}</li>
                                <li class="list-group-item">電話番号：<a href="tel:{{$order->tel}}">{{$order->tel}}</a></li>
                                <li class="list-group-item">メールアドレス：<a href="mailto:{{$email}}">{{$email}}</a></li>
                                <li class="list-group-item hidden">郵便番号：〒{{$order->post}}</li>
                                <li class="list-group-item hidden">詳細住所：{{$order->address}}</li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger text-center w-100 h-50" role="alert">
                        <h4 class="alert-heading">注文情報を見つかりませんでした</h4>
                    </div>
                @endif

            </div>
        </div>
</x-app-layout>
