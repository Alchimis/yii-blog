<?php

namespace app\models;

use app\models\AccessToken;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;

    public $createdAt;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = User::findOne([
            'id' => $id,
        ]);
        
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = AccessToken::findByToken($token);
        if (is_null($token)){
            return null;
        }
        $token->userId;
        $user = User::findOne([
            'id' => $token->userId,
        ]);
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return null;
    }

    /**
     * @param string $email
     * @return ?User $user
    */
    public static function findByEmail($email) 
    {   
        $user = User::findOne([
            'email' => $email,
        ]);
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function tableName() 
    {
        return '{{%user}}';
    }
}
