<?php

namespace app\models;

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
}