<?php

namespace app\validators;

use yii\base\Model;

class UsernameValidator {

    /**
     * 
     * @param Model $parent
     * 
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
    */
    public static function validateUsername($parent, $attribute, $params)
    {
        if (!is_string($attribute)) {
            $parent->addError('username should be string');
            return;
        }
        if (ctype_space($attribute)) {
            $parent->addError('username should not contain empty space');
            return;
        }
        if (strlen($attribute) <= 3) {
            $parent->addError('username should be longer than 8 characters');
            return;
        }
    }
}