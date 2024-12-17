<?php

namespace app\services;

use app\helpers\JsonProcessor;
use InvalidArgumentException;
use Yii;
use app\exceptions\HeaderNotSetException;
use app\models\AccessToken;
use app\models\User;
use yii\web\Request;
use app\exceptions\EntityNotFound;

class AuthenticateUserServiceBase extends \yii\base\BaseObject {

    const AUTH_HEADER = 'X-Auth-Token';
    const BEARER_REGEXP = "/^Bearer: ([A-Za-z0-9_-]{12,})$/";

    /**
      * Collects auth token from request headers and authenticate user by token.
      * 
      * header: X-Auth-Token 
      * @throws \app\exceptions\HeaderNotSetException
      * @throws \app\exceptions\EntityNotFound
      * @throws \InvalidArgumentExceptions
      *
      * @param Request $request
      * @return ?User $user
    */
    public static function authenticateUserFromRequest($request)
    {
        $headers = $request->getHeaders();
        $authHeader = $headers->get(AuthenticateUserServiceBase::AUTH_HEADER);
        if (is_null($authHeader)) {
            throw new HeaderNotSetException("Header ".AuthenticateUserServiceBase::AUTH_HEADER." not set");
        }
        
        if (is_array($authHeader)) {
            $authHeader = $authHeader[1];
        }

        if (!preg_match(AuthenticateUserServiceBase::BEARER_REGEXP, $authHeader, $matches)){
            throw new InvalidArgumentException("Invalid bearer header (".$authHeader."). It should be like: Bearer: <token>");
        }

        $token = $matches[1];
        if (!AccessToken::isTokenValid($token)) 
        {
            throw new InvalidArgumentException('invalid token');
        }
        $accessToken = AccessToken::findByToken($token);
        if (is_null($accessToken)) {
            throw EntityNotFound::entity('Token', ['token']);
        }
        
        $user = User::findOne(['id' => $accessToken->getUserId()]);
        return $user;
    }
    /**
     * @return ["user" => User, "accessToken" => AccessToken]
    */
    public static function registerUserFromParams($params)
    {
        
    }
}