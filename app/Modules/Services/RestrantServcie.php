<?php


namespace App\Modules\Services;


use Illuminate\Http\Request;

interface RestrantServcie
{
    public function getLists(Request $request);

    public function update(Request $request);

    public function getRestrantTakeDate($restrantId,$startDate);

    public function getRestrantCanTakeOutTimes($restrantId);

    public function getRestrantTakeOutDatas($restrantId, $date);

    //保存take out规则
    public function updateTakeOutRule(Request $request);
}
