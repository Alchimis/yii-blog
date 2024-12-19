<?php

namespace app\models;

use Yii;
use app\validators\UsernameValidator;
use yii\base\Model;
use app\validators\PasswordValidator;

class RegisterRequest extends Model {
    public $username;
    public $email;
    public $password;


    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            ['password', 'validatePassword'],
            ['email','email', 'message' => 'invalid email'],
            ['username', 'validateUsername']
        ];
    }


    public function validatePassword($attribute, $params)
    {
        return PasswordValidator::validatePassword($this, $attribute, $params);
    }

    public function validateUsername($attribute, $params)
    {
        return UsernameValidator::validateUsername($this, $attribute, $params);
    }

    public function makeUserFromRequest()
    {
        $user = new User();
        
        $hashedPassword = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $user->setAttributes([
            'email' => $this->email, 
            'password' => $this->password,
            'username' => $this->username,
            'role' => User::USER_ROLE,
            'hash' => $hashedPassword,
        ]);
        return $user;
    }
}