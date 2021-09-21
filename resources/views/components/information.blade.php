<div class="row">
    <div class="col-md-6">
        <div class="card mb-3" style="max-width: 540px;m">
            <div class="card-body">
                <h2 class="card-title">
                    {{$restrant->name}}
                </h2>
                <p class="card-text">
                    {{$restrant->PR}}
                </p>
                <table class="table">
                    <tbody>
                    <tr class="row">
                        <th class="col-sm-3">ジャンル</th>
                        <td class="col-sm-9">{{$restrant->type}}</td>
                    </tr>
                    <tr class="row">
                        <th class="col-sm-3">電話</th>
                        <td class="col-sm-9"><a href="tel:{{$restrant->tel}}">{{$restrant->tel}}</a></td>
                    </tr>
                    <tr class="row">
                        <th class="col-sm-3">住所</th>
                        <td class="col-sm-9">
                            〒{{$restrant->post}} {{$restrant->pref}}{{$restrant->city}}{{$restrant->street}}</td>
                    </tr>
                    <tr class="row">
                        <th class="col-sm-3">取扱ジビエ</th>
                        <td class="col-sm-9">{{$restrant->animal}}</td>
                    </tr>
                    @if(isset($restrant->workday))
                        <tr class="row">
                            <th class="col-sm-3">定休日</th>
                            <td class="col-sm-9">{{$restrant->workday}}</td>
                        </tr>
                    @endif
                    @if(isset($restrant->homepage))
                        <tr class="row">
                            <th class="col-sm-3">ホームページ</th>
                            <td class="col-sm-9"><a href="{{$restrant->homepage}}" target="_blank"
                                                    rel="noopener">{{$restrant->homepage}}</a></td>
                        </tr>
                    @endif
                    <tr class="row">
                        <th colspan="2" class="col-sm-12">外観</th>
                    </tr>
                    <tr class="row">
                        <td colspan="2" class="col-sm-12">
                            <div>
                                @if(isset($restrant->RestrantImage) && isset($restrant->RestrantImage->outPic))
                                    <img src="/uploads/{{$restrant->RestrantImage->outPic}}" class="card-img-bottom">
                                @else
                                    <img src="/img/outer.jpg" class="card-img-bottom">
                                @endif
                                @if(isset($restrant->RestrantImage->outComment))
                                    <pre>{{$restrant->RestrantImage->outComment}}</pre>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="row">
                        <th colspan="2" class="col-sm-12">内観</th>
                    </tr>
                    <tr class="row">
                        <td colspan="2" class="col-sm-12">
                            <div>
                                @if(isset($restrant->RestrantImage) && isset($restrant->RestrantImage->innerPic))
                                    <img src="/uploads/{{$restrant->RestrantImage->innerPic}}" class="card-img-bottom">
                                @else
                                    <img src="/img/inner.jpg" class="card-img-bottom">
                                @endif
                                @if(isset($restrant->RestrantImage->innerComment))
                                    <pre>{{$restrant->RestrantImage->innerComment}}</pre>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @if(isset($restrant->RestrantImage) && isset($restrant->RestrantImage->foodPic))
                        <tr class="row">
                            <th colspan="2" class="col-sm-12">料理</th>
                        </tr>
                        <tr class="row">
                            <td colspan="2" class="col-sm-12">
                                <div>
                                    <img src="/uploads/{{$restrant->RestrantImage->foodPic}}" class="card-img-bottom">
                                    @if(isset($restrant->RestrantImage->foodComment))
                                        <pre>{{$restrant->RestrantImage->foodComment}}</pre>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif

                    @if(isset($restrant->RestrantImage) && isset($restrant->RestrantImage->staffPic))
                        <tr class="row">
                            <th colspan="2" class="col-sm-12">スタッフ</th>
                        </tr>
                        <tr class="row">
                            <td colspan="2" class="col-sm-12">
                                <div>
                                    <img src="/uploads/{{$restrant->RestrantImage->staffPic}}" class="card-img-bottom">
                                    @if(isset($restrant->RestrantImage->staffComment))
                                        <pre>{{$restrant->RestrantImage->staffComment}}</pre>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <input type="hidden" id="latitude" value="{{$restrant->latitude}}"/>
                <input type="hidden" id="longitude" value="{{$restrant->longitude}}"/>
                <input type="hidden" id="post" value="{{$restrant->post}}"/>
                <input type="hidden" id="address" value="{{$restrant->pref}}{{$restrant->city}}{{$restrant->street}}"/>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <section id="maps" style="min-height: 600px;">
        </section>
    </div>
</div>
