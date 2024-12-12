<?php

namespace app\models;

use Yii;
use yii\base\Model;


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
        if (!is_string($attribute)) {
            $this->addError('password should be string');
            return;
        }
        if (strlen($attribute) <= 3) {
            $this->addError('password should be longer than 3 characters');
            return;
        }
    }
}