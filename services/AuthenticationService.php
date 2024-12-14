<?php

namespace app\services;

use Yii;
use app\models\LoginRequest;
use app\models\User;
use app\models\RegisterRequest;
use app\exceptions\AuthenticationException;
use app\exceptions\EntityNotFound;
use app\models\AccessToken;
use app\helpers\JsonProcessor;

class AuthenticationService extends \yii\base\BaseObject {
    /**
     * 
     * @param \yii\web\Request $request
    */
    public function register($request)
    {
        if (!$request->isPost) {
            return AuthenticationService::methodNotAllowed($request->method);
        }
        $registerRequest = new RegisterRequest();
        $registerRequest->setAttributes($request->post());
        if (!$registerRequest->validate()) {
            return JsonProcessor::processJson($registerRequest->errors);
        }
        $user = new User();
        
        $hashedPassword = Yii::$app->getSecurity()->generatePasswordHash($registerRequest->password);
        $user->setAttributes([
            'email' => $registerRequest->email, 
            'password' => $registerRequest->password,
            'username' => $registerRequest->username,
            'role' => User::USER_ROLE,
            'hash' => $hashedPassword,
        ]);
        try {
            $user->registerUser();
        } catch (\yii\db\IntegrityException $e) {
            return JsonProcessor::processJson(["error" => "user already exists"], 404);
        } catch (\Exception $e) {
            return JsonProcessor::processJson(["error" => "internal service error"], 500);
        }
        try {
            $accessToken = AccessToken::generateNewToken($user);
            $accessToken->save();
            return JsonProcessor::processJson(["accessToken"=>$accessToken->getOldAttributes()['token']]);
        } catch (\Exception $e) {
            return JsonProcessor::processJson(["error"=>"internal service error"], 500);
        }
    }

    /**
     * 
     * @param \yii\web\Request $request
    */
    public function login($request)
    {
        if (!$request->isPost) {
            return AuthenticationService::methodNotAllowed($request->method);
        }
        $loginRequest = new LoginRequest();
        $loginRequest->setAttributes($request->post());
        if (!$loginRequest->validate()) {
            return JsonProcessor::processJson($loginRequest->errors);
        }
        try{
            $accessToken = User::login($loginRequest->email, $loginRequest->password);
        } catch (AuthenticationException $e){
            return JsonProcessor::processJson(["error"=>"invalid email or password"]); 
        } catch (EntityNotFound $e) {
            return JsonProcessor::processJson(["error"=>"invalid email or password"]);
        }
        return JsonProcessor::processJson(["accessToken"=>$accessToken]);
    }


    /**
     * @param string $method
     * @return string $result
    */
    public static function methodNotAllowed($method)
    {
        return JsonProcessor::processJson(["error"=>"method ".$method." is not allowed"], 405);
    }
}