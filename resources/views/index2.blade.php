@extends('layouts.japon')
@section('title', 'Top')

@section('content')
    @include('components.frame.navbar')
    <main id="contents">
        <div class="new form-row">
        </div>
    </main>

    <div id="topcontrol" title="" style="position: fixed; bottom: 0px; right: 0px; opacity: 1; cursor: pointer;"><div id="scrolltotop"></div></div>
@endsection
@section('script')
    <script src="/js/search.js"></script>
    <script type="text/javascript">
        let animalVal = '{{$animal}}';
        let prefVal = '{{$animal}}';
        let cityVal = '{{$animal}}';
        let paymentTypeVal = '{{$paymentType}}';
        utils.ajaxSearchEvent(animalVal,prefVal,cityVal,paymentTypeVal);
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDK6GPBCxZEmYmCPckjSG4cjonOespvxE8&callback=utils.getCurrentPos&language=ja" defer></script>
@endsection
