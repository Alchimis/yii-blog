<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\validators\PasswordValidator;


class LoginRequest extends Model 
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email', 'message' => 'invalid email'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        return PasswordValidator::validatePassword($this, $attribute, $params);
    }
}