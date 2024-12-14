<?php

namespace app\models;

use Yii;
use app\exceptions\EntityNotFound;
use app\exceptions\AuthenticationException;
use app\exceptions\AlreadyExistsException;
use app\models\AccessToken;
use app\validators\PasswordValidator;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $hash;
    public $role;
    public $createdAt;

    
    const ADMIN_ROLE = "ADMIN"; 
    const USER_ROLE = "USER";

    /**
     * @param string $email
     * @param string $password
     * @return string $accessToken
    */
    public static function login($email, $password)
    {
        $user = User::findByEmail($email);
        if ($user === null) {
            throw new EntityNotFound("User", ["email"=>$email]);
        }
        if (!Yii::$app->getSecurity()->validatePassword($password, $user->getHash())){
            throw new AuthenticationException("invalid password");
        } 
        $user->id = $user->getAttribute('id');
        $token = AccessToken::generateNewToken($user);
        $token->save();
        return $token->getOldAttributes()['token'];
    }


    public function register()
    {
        $user = User::findOne([
            'email' => $this->email,
        ]);
        if ($user !== null) {
            throw new AlreadyExistsException('User already exists');
        }
        
        $this->save();
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'hash', 'role'], 'required'],
            ['email','string'],
            ['username','string'],
            ['password', 'string'],
            ['hash', 'string'],
            ['role', 'string']
            ['username', 'validateUsername'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        return PasswordValidator::validatePassword($this, $attribute, $params);
    }

    public function validateUsername($attribute, $params) 
    {
        if (!is_string($attribute)) {
            $this->addError('username should be string');
            return;
        }
        if (ctype_space($attribute)) {
            $this->addError('username should not contain empty space');
            return;
        }
        if (strlen($attribute) <= 3) {
            $this->addError('username should be longer than 3 characters');
            return;
        }
    } 

    
    public function registerUser()
    {
        $this->setAttribute('email', $this->email);
        $this->setAttribute('password', $this->password);
        $this->setAttribute('username', $this->username);
        $this->setAttribute('role', User::USER_ROLE);
        $this->setAttribute('hash', $this->hash);
        if (!$this->save()) {
            throw new AuthenticationException('error on saving user: '.json_encode($this->getErrors()));
        }
        $this->setAttributes($this->getOldAttributes(), false);
    }

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
        if (is_null($this->id))
        {
            return $this->getAttribute('id');
        }
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
    
    public static function tableName() 
    {
        return '{{%user}}';
    }

    public function getUsername()
    {
        if (is_null($this->username))
        {
            return $this->getAttribute('username');
        }
        return $this->username;
    }


    public function getHash()
    {
        if ($this->hash === null) 
        {
            return $this->getAttribute('hash');
        }
        return $this->hash;
    }
}
