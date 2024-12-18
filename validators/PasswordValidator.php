<?php

namespace app\validators;

use yii\base\Model;

class PasswordValidator {

    /**
     * 
     * @param Model $parent
     * 
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
    */
    public static function validatePassword($parent, $attribute, $params){
        if (!is_string($attribute)) {
            $parent->addError('password should be string');
            return false;
        }
        if (ctype_space($attribute)) {
            $parent->addError('password should not contain empty space');
            return false;
        }
        if (strlen($attribute) < 8) {
            $parent->addError('password should be longer than 8 characters have'.strlen($attribute));
            return false;
        }
        return true;
    } 
}