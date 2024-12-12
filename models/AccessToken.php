<?php

namespace app\models;

use Yii;

class AccessToken extends \yii\db\ActiveRecord {
    public $id;
    public $userId;
    public $token;
    public $createdAt;
    public $expiredAt;

    /**
     * @param string $token
     * @return ?AccessToken $tokens 
    */
    public static function findByToken($token)
    {
        $token = AccessToken::findOne([
            'token' => $token,
        ]);
        return $token;
    }

    public static function tableName() 
    {
        return '{{%accessToken}}';
    }

    /**
     * Проверяет не истёк ли срок действия токена. Если срок истечения не указан всегда возвращает true
     * 
     * @return bool $isExpired. 
     */
    public function isExpired()
    {
        $this->expiredAt;
        $now = (new \DateTime());
        $now->getTimestamp();
    }

    /**
     * 
     * @return int $expiredAt
    */
    public function getExpirationDateAsTimestamp()
    {
        return $this->expiredAt;
    } 

    /**
     * @param \app\models\User $user
     * @return AccessToken $token 
    */
    public static function generateNewToken($user)
    {
        $stringToken = Yii::$app->getSecurity()->generateRandomString(32);
        $token = new AccessToken();
        $token->userId = $user->id;
        $token->token = $stringToken;
        $token->save();
        return $token;
    }
}