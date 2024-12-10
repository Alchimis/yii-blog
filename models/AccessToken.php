<?php

namespace app\models;

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
}