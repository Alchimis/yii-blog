<?php

namespace app\services;

use Yii;
use app\models\LoginRequest;
use app\models\User;
use app\exceptions\AuthenticationException;
use app\exceptions\EntityNotFound;

class AuthenticationService extends \yii\base\BaseObject {
    /**
     * 
     * @param \yii\web\Request $request
    */
    public function register($request)
    {
        
    return "{
    \"hello\": \"world\" 
}";
    }

    /**
     * 
     * @param \yii\web\Request $request
    */
    public function login($request)
    {
        if (!$request->isPost) {
            return "{\"method\": \"POST\"}";
        }
        $loginRequest = new LoginRequest();
        $loginRequest->setAttributes($request->post());
        if (!$loginRequest->validate()) {
            Yii::$app->response->format = \Yii\web\Response::FORMAT_JSON;
            Yii::$app->response->statusCode = 404;
            return json_encode($loginRequest->errors);
        }
        try{
            $accessToken = User::login($loginRequest->email, $loginRequest->password);
        } catch (AuthenticationException $e){
            Yii::$app->response->statusCode = 404;
            return "{\"error\":\"invalid email or password\"}"; 
        } catch (EntityNotFound $e) {
            Yii::$app->response->statusCode = 404;
            return "{\"error\":\"invalid email or password\"}";
        }
        Yii::$app->response->format = \Yii\web\Response::FORMAT_JSON;
        return "{\"accessToken\":\"".$accessToken."\"}";
    }
}