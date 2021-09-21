let animal = 'ホンシュウ鹿,猪,ツキノワグマ,ヒグマ,アナグマ,ハクビシン,兎,ヌートリア,タヌキ,キョン,エゾ鹿,トド,鴨,カラス';
let type = 'イタリアン,フレンチ,中華料理,日本料理,その他';
let paymentType = 'イートイン,現地決済,郵送';
let config = {
    no_results_text: 'データを見つかりません。',
    allow_single_deselect: true
}


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


/**
 * Returns the Popup class.
 *
 * Unfortunately, the Popup class can only be defined after
 * google.maps.OverlayView is defined, when the Maps API is loaded.
 * This function should be called by initMap.
 */
function createPopupClass() {
    /**
     * A customized popup on the map.
     * @param {!google.maps.LatLng} position
     * @param {!Element} content The bubble div.
     * @constructor
     * @extends {google.maps.OverlayView}
     */
    function Popup(position, content) {
        this.position = position;

        content.classList.add('popup-bubble');

        // This zero-height div is positioned at the bottom of the bubble.
        var bubbleAnchor = document.createElement('div');
        bubbleAnchor.classList.add('popup-bubble-anchor');
        bubbleAnchor.appendChild(content);

        // This zero-height div is positioned at the bottom of the tip.
        this.containerDiv = document.createElement('div');
        this.containerDiv.classList.add('popup-container');
        this.containerDiv.appendChild(bubbleAnchor);

        // Optionally stop clicks, etc., from bubbling up to the map.
        google.maps.OverlayView.preventMapHitsAndGesturesFrom(this.containerDiv);
    }

    // ES5 magic to extend google.maps.OverlayView.
    Popup.prototype = Object.create(google.maps.OverlayView.prototype);

    /** Called when the popup is added to the map. */
    Popup.prototype.onAdd = function () {
        this.getPanes().floatPane.appendChild(this.containerDiv);
    };

    /** Called when the popup is removed from the map. */
    Popup.prototype.onRemove = function () {
        if (this.containerDiv.parentElement) {
            this.containerDiv.parentElement.removeChild(this.containerDiv);
        }
    };

    /** Called each frame when the popup needs to draw itself. */
    Popup.prototype.draw = function () {
        var divPosition = this.getProjection().fromLatLngToDivPixel(this.position);

        // Hide the popup when it is far out of view.
        var display =
            Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000 ?
                'block' :
                'none';

        if (display === 'block') {
            this.containerDiv.style.left = divPosition.x + 'px';
            this.containerDiv.style.top = divPosition.y + 'px';
        }
        if (this.containerDiv.style.display !== display) {
            this.containerDiv.style.display = display;
        }
    };

    return Popup;
}

var utils = {

    pageNo: 1,
    pageSize: 50,
    lastPage: 1,
    loading: true,
    distance: 10,
    instance: 60,
    data: {},
    index:null,


    //食材の初期化
    initAnimal: function () {
        var animals = animal.split(',');
        var html = ['<option value=""></option>'];
        for (var i = 0; i < animals.length; i++) {
            html.push(`<option value="${animals[i]}">${animals[i]}</option>`);
        }
        $('#ddlAnimal').html(html).chosen(config);
    },

    //ジャンルの初期化する
    initType: function () {
        html = ['<option value=""></option>'];
        var types = type.split(',');
        for (var i = 0; i < types.length; i++) {
            html.push(`<option value="${types[i]}">${types[i]}</option>`);
        }
        $('#ddlType').html(html.join('')).chosen(config);
    },

    //ジャンルの初期化する
    initPaymentType: function () {
        html = ['<option value=""></option>'];
        var types = paymentType.split(',');
        for (var i = 0; i < types.length; i++) {
            html.push(`<option value="${types[i]}">${types[i]}</option>`);
        }
        $('#ddlPaymentType').html(html.join('')).chosen(config);
    },

    //都道府を初期化する
    initPref: function () {
        $('#ddlPref').chosen(config).change(function () {
            var pref = this.value;
            var html = ['<option value=""></option>'];
            var ddlCity = $('#ddlCity');
            if (pref) {
                $.ajax({
                    url: 'getCityListApi',
                    data: {pref: pref}
                }).done(function (res) {
                    $.each(res, function (index, item) {
                        html.push(`<option value="${item.city}">${item.city}</option>`);
                    });

                    ddlCity.html(html.join('')).trigger('chosen:updated');
                }).fail(function (error) {

                })
            } else {
                ddlCity.html(html).trigger('chosen:updated');
            }
        });

    },

    initRestrantList: function () {
        utils.loading = false;
        utils.pageNo = 1;
        utils.data = {};
        $('.new').html('');
    },

    //県を初期化する
    initCity: function () {
        $('#ddlCity').chosen(config);
    },

    goDetails: function (id) {
        if (utils.opener) {
            utils.opener.close();
        }
        utils.opener = window.open('detail?id=' + id);
    },

    getRestrantList: function () {
        utils.data.pageNo = utils.pageNo;
        utils.data.pageSize = utils.pageSize;
        utils.data.animal = $('#ddlAnimal').val();
        utils.data.pref = $('#ddlPref').val();
        utils.data.city = $('#ddlCity').val();
        utils.data.type = $('#ddlType').val();
        utils.data.paymentType = $('#ddlPaymentType').val()

        console.info(utils.data)

        $.ajax({
            url: 'getRestrantPageListApi',
            type: 'POST',
            data: utils.data,
            beforeSend: function () {
            }
        }).done(function (res) {

            utils.pageNo = res.pageNo;
            utils.lastPage = res.lastPage;

            var html = [];

            $.each(res.list, function (index, item) {
                html.push(`<div class="setsumei col-sm-4 ml-2" onclick="utils.goDetails('${item.id}')">
                <p class="midashi"><span style="color:#162e7e;">${item.name || '店舗名なし'}</span></p>
                <p class="pr">${item.PR || 'PRなし'}</p>
                <p class="details">詳細をもっと見る</p>
                <div class="foto"><img src="${item.outPic ? '/uploads/' + item.outPic : 'img/original.jpg'}" alt=""></div>
                </div>`);
            })

            $('.new').append(html.join(''));
            utils.loading = false;
            if(utils.index!=null){
                layer.close(utils.index)
            }

        }).fail(function (res) {
            console.info(res);
            if(utils.index!=null){
                layer.close(utils.index)
            }
        });
    },

    //検索をする
    ajaxSearchEvent: function (animalVal,prefVal,cityVal,paymentTypeVal) {


        utils.data.animal = animalVal;
        utils.data.pref = prefVal
        utils.data.city = cityVal
        utils.data.paymentType = paymentTypeVal

        //console.info(utils.data.animal)
        $(document).ready(function () {

            $('#ddlAnimal').val(utils.data.animal);
            $("#ddlAnimal").trigger("chosen:updated");
            $('#ddlPref').val(utils.data.pref);
            $("#ddlPref").trigger("chosen:updated");
            $('#ddlCity').val(utils.data.city);
            $("#ddlCity").trigger("chosen:updated");
            $('#ddlPaymentType').val(utils.data.paymentType);
            $("#ddlPaymentType").trigger("chosen:updated");


            //有条件的情况下
            if(utils.data.animal!=""
                || utils.data.pref!=""
                && utils.data.city.length!=""
                && utils.data.paymentType!=""){

                utils.getRestrantList();

            }

            $('.btn-search').on('click', function () {
                utils.initRestrantList();
                utils.getRestrantList();
            })

            $('#btnBack').show();
            $('#btnBack').find('button').click(function () {
                location.href="/";
            })

            $('#topcontrol').click(function () {
                $(document).scrollTop(0)
            })
        })
    },

    topSearchEvent: function () {
        $('.btn-search').on('click', function () {
            utils.data.pageNo = utils.pageNo;
            utils.data.pageSize = utils.pageSize;
            utils.data.animal = $('#ddlAnimal').val();
            utils.data.pref = $('#ddlPref').val();
            utils.data.city = $('#ddlCity').val();
            utils.data.paymentType = $('#ddlPaymentType').val()
            location.href='/searchResult?' + $.param(utils.data)
        })
    },

    infinite: function (callback) {
        $(window).on('scroll', function () {

            //公里查询的情况，不触发
            if(utils.data.animal==''
                && utils.data.pref==''
                && utils.data.city==''
                && utils.data.paymentType==''){
                return;
            }

            if ($(document).scrollTop() + $(window).height() > $(document).height() - utils.instance) {

                if (utils.loading) {
                    return;
                }
                utils.loading = true;

                if (utils.pageNo > utils.lastPage) {
                    return;
                }
                utils.pageNo++;
                utils.getRestrantList();
            }
        });
    },
    initMap: function () {

        var post = $('#post').val();
        var address = $('#address').val();
        var latitude = $('#latitude').val();
        var longitude = $('#longitude').val();

        var nowLatLng = new google.maps.LatLng(latitude, longitude);
        var option = {
            center: nowLatLng,
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        //var maps = $('#maps');
        var map = new google.maps.Map(document.getElementById('maps'), option);

        var marker = new google.maps.Marker({
            position: nowLatLng,
            animation: google.maps.Animation.DROP

        });
        marker.setMap(map);
        marker.addListener('click', toggleBounce);

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        var infowindow = new google.maps.InfoWindow({
            content: '<span>〒' + post + '<br />' + address + '</span><br /><br /><a class="route" href="javascript:utils.goToMaps()">拡大地図を表示</a>'
        });

        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
    },

    goToMaps: function () {
        var address = $('#address').val();
        if (address) {
            var url = "https://www.google.com/maps/search/?api=1&query=" + address;
            window.open(url);
        }
    },
    getCurrentPos: function () {
        utils.loading = true;

        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };

        //loading层

        utils.index = layer.load(1, {
            shade: [0.7,'#000'] //0.1透明度的白色背景
        });


        // 位置情報取得が成功したら
        var success = (pos) => {

            // マップオブジェクトの変数を空で宣言
            var nowLat = pos.coords.latitude;//緯度
            var nowLng = pos.coords.longitude;//経度

            console.info('現在地');
            console.info(nowLat);
            console.info(nowLng);

            $.ajax({
                url: 'getLocationRangeApi',
                data: {'lng': nowLng, 'lat': nowLat, 'distance': utils.distance}
            }).done(function (res) {
                utils.data.distance = utils.distance;
                utils.data.lng = nowLng;
                utils.data.lat = nowLat;
                utils.data.left_top_lat = res.left_top_lat;
                utils.data.left_top_lng = res.left_top_lng;
                utils.data.right_bottom_lat = res.right_bottom_lat;
                utils.data.right_bottom_lng = res.right_bottom_lng;

                //5公里数的饭店
                if(utils.data.animal==''
                    && utils.data.pref==''
                    && utils.data.city==''
                    && utils.data.paymentType==''){

                    $.ajax({
                        url:'getRestrantRangeListApi',
                        type:'POST',
                        data:utils.data,
                        beforeSend:function(){}
                    }).done(function(res){
                        var html = [];
                        $.each(res,function(index,item){
                            html.push(`<div class="setsumei col-sm-4 ml-2" onclick="utils.goDetails('${item.res.id}')">
                        <p class="midashi"><span style="color:#162e7e;">${item.res.name || '店舗名なし'}</span></p>
                        <p class="pr">${item.res.PR || 'PRなし'}</p>
                        <p class="details">詳細をもっと見る</p>
                        <div class="foto"><img src="${item.res.outPic ? '/uploads/'+item.res.outPic : 'img/original.jpg'}"  alt=""></div>
                        </div>`);
                        })

                        $('.new').append(html.join(''));
                        layer.close(index)

                    }).fail(function(res){
                        console.info(res);
                        layer.close(utils.index)
                    });
                }

                //utils.getRestrantList();

            }).fail(function (res) {
                utils.getRestrantList();
                layer.close(utils.index)
            })

        }

        // 位置情報取得が失敗したら
        var error = (err) => {
            // エラーメッセージ
            //$('.btn-search').trigger('click');
            if(utils.index!=null){
                layer.close(utils.index)
            }

            //utils.initRestrantList();
            utils.getRestrantList();

        }
        navigator.geolocation.getCurrentPosition(success, error, options);

    }
}

$(document).ready(function () {
    utils.initAnimal();
    utils.initType();
    utils.initPaymentType();
    utils.initPref();
    utils.initCity();
    utils.infinite();
})
