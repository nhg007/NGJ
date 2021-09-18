<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\Search;

header('X-Frame-Options: ALLOW-FROM');

class IndexController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    //
    public function index(Request $request)
    {
        $restrant = new Search($request);
        $result = $restrant->getAllPrefs();

        return view('index',['prefList' => $result]);
    }

    public function getCityListApi(Request $request)
    {
        //都道府県を取得する

        $restrant = new Search($request);
        return response()->json($restrant->getCityList());

    }

    public function getRestrantPageListApi(Request $request){
        $restrant = new Search($request);
        return response()->json($restrant->getRestrantPagerList());
    }

    public function getRestrantRangeListApi(Request $request){

        $restrant = new Search($request);
        $lng = $request['lng'];
        $lat = $request['lat'];
        $distance =  $request['distance'];
        $list = $restrant->getRestrantListByRange();

        $result = [];
        foreach ($list as $value){
            //$to = [$value->longitude,$value->latitude];
            $cur =  $this->GetDistance($value->latitude,$value->longitude,$lat, $lng);
            if($cur <= $distance){
                array_push($result, ["distance" =>$cur,"res"=>$value]);
            }
        }
        return response()->json($result);
    }


    /**
     * 根据经纬度算距离，返回结果单位是公里，先纬度，后经度
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float|int
     */
        public function GetDistance($lat1, $lng1, $lat2, $lng2)
        {
            $EARTH_RADIUS = 6378.137;

            $radLat1 = $this->rad($lat1);
            $radLat2 = $this->rad($lat2);
            $a = $radLat1 - $radLat2;
            $b = $this->rad($lng1) - $this->rad($lng2);
            $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
            $s = $s * $EARTH_RADIUS;
            $s = round($s * 10000) / 10000;

            return $s;
        }

        private function rad($d)
        {
            return $d * M_PI / 180.0;
        }



    public function detail(Request $request){
        $restrant = new Search($request);
        $result = $restrant->getRestrantInfo();
        $restrantId =$request->query("id");
        $products = $this->productService->getListsByRestrantId($restrantId);

        $prefList = $restrant->getAllPrefs();

        return view('detail',['restrant' => $result,"products" =>$products,"restrantId"=>$restrantId,'prefList' => $prefList]);
    }

    public function getLocationRangeApi(Request $request){
        $lng = $request["lng"];
        $lat = $request["lat"];
        $distance =  $request["distance"];

        $point = $this->returnSquarePoint($lng,$lat,$distance);
        $right_bottom_lat = $point['right_bottom']['lat'];
        $left_top_lat = $point['left_top']['lat'];
        $left_top_lng = $point['left_top']['lng'];
        $right_bottom_lng = $point['right_bottom']['lng'];

        return response()->json(
            [ "right_bottom_lat" => $right_bottom_lat,
        "left_top_lat" => $left_top_lat,
        "left_top_lng" => $left_top_lng,
        "right_bottom_lng" => $right_bottom_lng,
        ]);
    }

    /**
     * @param $lng
     * @param $lat
     * @param float $distance 单位：km
     * @return array
     * 根据传入的经纬度，和距离范围，返回所有在距离范围内的经纬度的取值范围
     */
    function returnSquarePoint($lng, $lat,$distance = 1){
        $earthRadius = 6378.137;//单位km
        $dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);
        return array(
            'left_top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right_top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left_bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right_bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng),
        );
    }
}
