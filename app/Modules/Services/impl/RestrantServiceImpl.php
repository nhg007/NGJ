<?php


namespace App\Modules\Services\impl;


use App\Models\Restrant;
use App\Models\RestrantImage;
use App\Models\RestrantTakeOut;
use App\Models\Vo\JsonResult;
use App\Modules\Services\RestrantServcie;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class RestrantServiceImpl implements RestrantServcie
{
    public function getLists(Request $request)
    {
        $keyword = $request->get('name');
        $list = null;
        if (isset($keyword)) {
            $list = Restrant::where([
                ['name', 'like', '%' . $keyword . '%'],
            ])->orderBy('created_at', 'asc')
                ->paginate(20);
        } else {

            $list = Restrant::orderBy('created_at', 'asc')->paginate(20);
        }

        return $list;
    }

    public function update(Request $request)
    {
        //レストランの主キー
        $id = Auth::guard('restrant')->id();

        $outFiles = $request->get('outFiles');
        $innerFiles = $request->get('innerFiles');
        $staffFiles = $request->get('staffFiles');


        //レストラン情報を更新する
        $restrant = Restrant::find($id);
        $restrant->name = $request->get("name");
        $restrant->homepage =  $request->get("homepage");
        $restrant->type =  $request->get("type");
        $restrant->paymentType =  $request->get("paymentType");
        $restrant->post =  $request->get("post");
        $restrant->pref =  $request->get("pref");
        $restrant->city = $request->get("city");
        $restrant->street =  $request->get("street");
        $restrant->email = $request->get("email");
        $restrant->owner = $request->get("owner");
        $restrant->stuff1 = $request->get("stuff1");
        $restrant->fax = $request->get("fax");
        $restrant->PR = $request->get("PR");
        $restrant->latitude = $request->get("latitude");
        $restrant->latitude = $request->get("latitude");
        $restrant->comment = $request->get("comment");
        $restrant->workday = $request->get("workday");;
        $restrant->animal = $request->get("animal");
        $restrant->id = $request->get("id");
        $restrant->ranking = $request->get("ranking");
        $restrant->save();

        //レストランの写真を保存する
        $restrantImage = RestrantImage::firstWhere('restrant_id', $id);
        $message = "更新成功しました";
        if (!isset($restrantImage)) {
            $restrantImage = new RestrantImage();
        } else {
            //外观
            if ($restrantImage->outPic != $outFiles) {
                //删除文件
                if (Storage::disk('uploads')->exists($restrantImage->outPic)) {
                    Storage::disk('uploads')->delete($restrantImage->outPic);
                }
            }
            //内观
            if ($restrantImage->innerPic != $innerFiles) {
                //删除文件
                if (Storage::disk('uploads')->exists($restrantImage->innerPic)) {
                    Storage::disk('uploads')->delete($restrantImage->innerPic);
                }
            }

            //staff
            if ($restrantImage->staffPic != $staffFiles) {
                //删除文件
                if (Storage::disk('uploads')->exists($restrantImage->staffPic)) {
                    Storage::disk('uploads')->delete($restrantImage->staffPic);
                }
            }
        }

        $restrantImage->restrant_id = $id;
        $restrantImage->outPic = $outFiles;
        $restrantImage->innerPic = $innerFiles;
        $restrantImage->staffPic = $staffFiles;

        $restrantImage->save();
        return response()->json(JsonResult::success($message, 200, $restrantImage));
    }

    public function getRestrantTakeDate($restrantId, $startDate)
    {
        $list = RestrantTakeOut::where([
            ['restrant_id', '=', $restrantId],
            ['take_date', '>=', $startDate]
        ])->groupBy('take_date')->pluck('take_date');
        return response()->json(JsonResult::success("取得しました。", 200, $list));
    }

    public function getRestrantCanTakeOutTimes($restrantId)
    {
        $dt = Carbon::now();
        $startDate = $dt->format('Y-m-d');
        $hour = intval($dt->format('H'));
        $list = RestrantTakeOut::where([
            ['restrant_id', '=', $restrantId],
            ['take_date', '>', $startDate],
        ])->orWhere(function ($query) use ($hour, $startDate) {
            $query->where('take_date', '=', $startDate)->where('end_hour', '>', $hour);
        })->where('number', '>', 0)->get();
        return $list;
    }

    public function getRestrantTakeOutDatas($restrantId, $date)
    {
        $list = RestrantTakeOut::where([
            ['restrant_id', '=', $restrantId],
            ['take_date', '=', $date]
        ])->get();
        return response()->json(JsonResult::success("取得しました。", 200, $list));
    }


    public function updateTakeOutRule(Request $request)
    {
        // TODO: Implement updateTakeOutRule() method.
        $timelines = $request->get('timelines');
        $takeDate = $request->get('takeDate');
        $restrantId =  Auth::guard('restrant')->id();

        //删除已有的数据
        RestrantTakeOut::where([
            ['restrant_id', '=', $restrantId],
            ['take_date', '=', $takeDate]
        ])->delete();

        //保存数据
        foreach ($timelines as $key => $value) {
            $restrantTakeOut =  new RestrantTakeOut();
            $restrantTakeOut->restrant_id = $restrantId;
            $restrantTakeOut->take_date = $takeDate;
            $restrantTakeOut->start_time = $value['start_time'];
            $restrantTakeOut->end_time = $value['end_time'];
            list($startHour) = preg_split("/:/", $value['start_time']);
            list($endHour) = preg_split("/:/", $value['end_time']);
            $restrantTakeOut->start_hour = $startHour;
            $restrantTakeOut->end_hour = $endHour;
            $restrantTakeOut->number = $value['number'];
            $restrantTakeOut->save();
        }
        return response()->json(JsonResult::success("保存しました。", 200, null));
    }

    public function getRestrantInfo($restrantId)
    {
        return Restrant::find($restrantId);
    }


}
