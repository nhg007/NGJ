@extends('layouts.japon')
@section('title', '店舗詳細')

@push('css')
    <style>
        #footer {
            position: static !important;
        }
    </style>

@endpush
@section('content')

<div class="container">
    @include('components.information')
    @include('components.products')
</div>
@endsection
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDK6GPBCxZEmYmCPckjSG4cjonOespvxE8&callback=utils.initMap&language=ja"></script>
<script>
var geocoder = new google.maps.Geocoder();
// ジオコーディング　検索実行
geocoder.geocode({"address" : '西葛西5-11-15　ベイシティ西葛西1001'}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        var lat = results[0].geometry.location.lat();//緯度を取得
        var lng = results[0].geometry.location.lng();//経度を取得
        console.log("addressOnBlur===lat=="+lat);
        console.log("addressOnBlur===lng=="+lng);
    }
});
</script>

@endsection
