<?php

namespace app\models;

use Yii;
use app\models\User;

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
     * @param \DateTime 
     * @return AccessToken $token 
    */
    public static function generateNewToken($user)
    {
        $stringToken = Yii::$app->getSecurity()->generateRandomString(32);
        $token = new AccessToken();
        $token->setAttributes([
            'token' => $stringToken,
            'userId' => $user->getId(),
        ]);
        $token->setAttribute('token', $stringToken);
        $token->setAttribute('userId', $user->getId());
        return $token;
    }

    public function getAttachedUser()
    {
        return User::findOne(['id'=>$this->getUserId()]);
    }

    public function getId()
    {
        if (is_null($this->id))
        {
            return $this->getAttribute('id');
        }
        return $this->id;
    }

    public function getUserId()
    {
        if (is_null($this->userId))
        {
            return $this->getAttribute('userId');
        }
        return $this->userId;
    }

    public function getToken()
    {
        if (is_null($this->token))
        {
            return $this->getAttribute('token');
        }
        return $this->token;
    }

    public function getCreatedAt()
    {
        if (is_null($this->createdAt))
        {
            return $this->getAttribute('createdAt');
        }
        return $this->createdAt;
    }

    public function getExpiredAr()
    {
        if (is_null($this->expiredAt))
        {
            return $this->getAttribute('expiredAt');
        }
        return $this->expiredAt;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => $this->getUserId()]);
    }

    /**
     * @param string $token
     * @return bool
    */
    public static function isTokenValid($token)
    {
        return true;
    }
}