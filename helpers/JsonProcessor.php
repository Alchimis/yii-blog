<?php

namespace app\helpers;

use Yii;
use yii\helpers\Json;
class JsonProcessor {
    /**
     * @param string|mixed $data. Should be valid json.
     * @param int $statusCode. html status code
     * @return string 
    */
    public static function processJson($data, $statusCode = 200)
    {
        if (!is_string($data)){
            try {
                $data = Json::encode($data);
            } catch (yii\base\InvalidArgumentException $e) {
                return "";
            }
        }
        Yii::$app->response->statusCode = $statusCode;
        Yii::$app->response->format = Yii\web\Response::FORMAT_JSON;
        return $data;
    }
}