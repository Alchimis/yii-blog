<?php

namespace app\controllers;

class AuthenticationController extends \yii\rest\Controller
{
    /**
     * @var \app\services\AuthenticationService
    */
    private $authenticationService;
    private function getAuthenticationService() 
    {
        if ($this->authenticationService === null){
            $this->authenticationService = \Yii::$container->get(\app\services\AuthenticationService::class);
        }
        return $this->authenticationService;
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegister()
    {
        \Yii::$app->response->format = \Yii\web\Response::FORMAT_JSON;
        return $this->getAuthenticationService()->register($this->request);
    }

    public function actionLogin()
    {
        return  $this->getAuthenticationService()->login($this->request);
    }
}
