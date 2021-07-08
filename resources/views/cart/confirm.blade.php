<?php $var = 0; ?>

<x-app-layout>
    <x-slot name="header">
{{--        <h2 class="font-semibold text-xl text-gray-800 leading-tight">--}}
{{--            {{ __('注文内容の確認') }}--}}
{{--        </h2>--}}
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            <div class="mt-10 sm:mt-0" >

                <div class="mt-5 md:mt-0 md:col-span-2 hide" id="cartItems" >
                    <form action="/payment" method="POST">
                        @csrf
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-3 bg-white sm:p-6" class="card">
                                @if(sizeof($carts) == 0)
                                    <div class=" my-3 py-3 px-2 text-center">
                                        カートの中身は空です
                                    </div>
                                @else
                                    <div class="card-header">
                                        [{{$restrant->name}}] <br/>注文内容の確認
                                    </div>

                                <ul class="list-unstyled">

                                        @foreach($carts as $cart)
                                            <input type="hidden" value="{{$var += ($cart->price * $cart->number)}}"/>
                                            <li class="media my-3 py-3 px-2">
                                                <img src="/uploads/{{$cart->product->picture_path}}">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">{{$cart->product->name}}</h5>
                                                    <p class="mb-0">内容量:{{$cart->product->column}}</p>
                                                    <p>
                                                        <span class="goods-price"></span>¥{{number_format($cart->price)}} × {{$cart->number}}
                                                    </p>
                                                </div>
                                            </li>
                                            <input type="hidden" id="restrantId" class="restrantId" value="{{$restrant->id}}" />
                                            <input type="hidden" id="hdCartIds" class="cartIds" value="{{$cart->id}}" />
                                        @endforeach
                                </ul>
                                @endif

                            </div>
                            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <span style="margin-right: 20px">支払額: <span class="goods-price">¥{{number_format($var)}}</span></span>
                                    <input type="hidden" name="totalPrice" class="totalPrice" value="{{$var}}" />
                                    <input type="hidden" class="goods_number"  value="{{$goodNumber}}" />
                                    <input type="hidden" name="order_sn" class="order_sn" />
                                    <input type="hidden" name="order_id" class="order_id" />
                                    <button type="button" id="btnConfirm" class="inline-flex items-center px-4 py-2 bg-green-500
                                     border border-transparent rounded-md
                                     font-semibold text-xs text-white uppercase
                                      tracking-widest hover:bg-green-600 active:bg-green-700
                                       focus:outline-none focus:border-green-800
                                        focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                        注文確認
                                    </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="cartConfirm"
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
            <script type="text/javascript">
                $(document).ready(function () {

                    //需要检查一下数据的有效性，没有数据的情况下，直接返回到MyPageŒ

                    cart.confirm();
                })
            </script>
        @endpush
</x-app-layout>
