<?php


namespace App\Models\Vo;


class JsonResult
{

    public static function success($message,$status = 200,$data = null){
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function fail($message,$status = 500,$data = null){
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}
