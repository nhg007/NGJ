<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Restrant extends Model
{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    //全ての都道府を取得する
    public function getAllPrefs(){
        return \DB::table('restrants')->select(\DB::raw('pref'))->distinct()->get();
    }

    //Cityを取得する
    public function getCityList(){

        $pref = $this->request["pref"];

       return \DB::table('restrants')
         ->select(\DB::raw('city'))
            ->where('pref',$pref)
            ->where('city','<>', '')
            ->where('city', '<>', 'NULL')
            ->distinct()
            ->get();
    }


    public function getRestrantPagerList(){
        $pageNo = $this->request['pageNo'];
        $pageSize = $this->request['pageSize'];
        $animal = $this->request['animal'];
        $pref = $this->request['pref'];
        $city = $this->request['city'];
        $type = $this->request['type'];

        //Pager
        $restrant = \DB::table('restrants')->select(\DB::raw('count(1) as count'));

        if(isset($animal)){
            $restrant = $restrant->where('animal','like', '%'.$animal.'%');
        }

        if(isset($pref)){
            $restrant = $restrant->where('pref',$pref);
        }

        if(isset($city)){
            $restrant = $restrant->where('city',$city);
        }
        if(isset($type)){
            $restrant = $restrant->where('type',$type);
        }

        if(isset($right_bottom_lat)){
            $restrant = $restrant->where('latitude','>',$right_bottom_lat);
        }

        if(isset($left_top_lat)){
            $restrant = $restrant->where('latitude','<',$left_top_lat);
        }

        if(isset($left_top_lng)){
            $restrant = $restrant->where('longitude','>',$left_top_lng);
        }

        if(isset($right_bottom_lng)){
            $restrant = $restrant->where('longitude','<',$right_bottom_lng);
        }

        $totalCount = $restrant->first()->count;

        $list = \DB::table('restrants')->select(\DB::raw('id,name,PR'));
        if(isset($animal)){
            $list = $list->where('animal','like', '%'.$animal.'%');
        }

        if(isset($pref)){
            $list = $list->where('pref',$pref);
        }

        if(isset($city)){
            $list = $list->where('city',$city);
        }
        if(isset($type)){
            $list = $list->where('type',$type);
        }


        $list = $list->offset(($pageNo - 1) * $pageSize)->limit($pageSize)->get();


        $lastPage = max((int) ceil($totalCount * 1.0 / $pageSize), 1);

        $result = [
            'totalCount' => $totalCount,
            'pageNo'=>$pageNo,
            'lastPage' => $lastPage,
            'list'=>$list
        ];

        return $result;
    }

    public function getRestrantListByRange(){

        $left_top_lat = $this->request['left_top_lat'];
        $left_top_lng = $this->request['left_top_lng'];
        $right_bottom_lat = $this->request['right_bottom_lat'];
        $right_bottom_lng = $this->request['right_bottom_lng'];

        $list = \DB::table('restrants')->select(\DB::raw('id,name,PR,latitude,longitude'));

        if(isset($right_bottom_lat)){
            $list = $list->where('latitude','>',$right_bottom_lat);
        }

        if(isset($left_top_lat)){
            $list = $list->where('latitude','<',$left_top_lat);
        }

        if(isset($left_top_lng)){
            $list = $list->where('longitude','>',$left_top_lng);
        }

        if(isset($right_bottom_lng)){
            $list = $list->where('longitude','<',$right_bottom_lng);
        }

        return $list->get();

    }

    public function getRestrantInfo(){

        $id =  $this->request['id'];

        return \DB::table('restrants')->where('id',$id)->first();
    }

}
