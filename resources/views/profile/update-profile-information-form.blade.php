<div wire:id="IotjdJ2qDvURy5b40Jjh" class="md:grid md:grid-cols-3 md:gap-6">
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium text-gray-900">会員情報</h3>

            <p class="mt-1 text-sm text-gray-600">
                アカウントのプロファイル情報とメールアドレスを更新します。
            </p>
        </div>
    </div>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form action="/user/profile-information">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                名前 <span class="single-total">*</span>
                            </label>


                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                id="name" name="name" type="text" value="{{$this->user->name}}"  autocomplete="name">
                        </div>

                        <!-- Email -->
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block font-medium text-sm text-gray-700" for="email">
                                Eメールアドレス <span class="single-total">*</span>
                            </label>
                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                id="email" name="email" type="email" value="{{$this->user->email}}" >
                        </div>

                        <!-- 電話番号 -->
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block font-medium text-sm text-gray-700" for="telphone">
                                電話番号 <span class="single-total">*</span>
                            </label>
                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                id="telphone" name="telphone" type="text" value="{{$this->user->telphone}}">
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label class="block font-medium text-sm text-gray-700" for="post">
                                郵便番号 <span class="single-total">*</span>
                            </label>
                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 w-60"
                                id="post" name="post" type="text" value="{{$this->user->post}}"  placeholder="郵便番号を入力してくだい">
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 bg-green-600 hover:bg-green-500 active:bg-green-700 focus:border-green-700 focus:shadow-outline-green w-15"
                                    id="zipSearch">
                                郵便番号検索
                            </button>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block font-medium text-sm text-gray-700" for="pref">
                                都道府県 <span class="single-total">*</span>
                            </label>
                            <select class="form-control col-8" name="pref" id="pref" data-value="{{$this->user->pref}}">

                            </select>
                        </div>
                        <!-- 住宅 -->
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block font-medium text-sm text-gray-700" for="address">
                                住宅 <span class="single-total">*</span>
                            </label>
                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                id="address" name="address" type="text" value="{{$this->user->address}}" >
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <div id="message" style="display: none;" class="text-sm text-gray-600 mr-3">
                        更新しました
                    </div>

                    <button type="button" id="btnSaveProfile"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:shadow-outline-green"
                            >
                        更新
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('modals')
    <script src="{{url('/js/ajaxzip3.js')}}"></script>
    <script src="{{url('/js/jquery.serializejson.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            profile.init()
        })
    </script>
@endpush

