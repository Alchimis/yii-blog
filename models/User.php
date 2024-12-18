<?php

namespace app\models;

use app\models\AccessToken;
use Yii;
use app\exceptions\EntityNotFound;
use app\exceptions\AuthenticationException;
use app\exceptions\AlreadyExistsException;
use app\validators\PasswordValidator;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $hash;
    private $role;
    private $createdAt;

    
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
            throw EntityNotFound::entity("User", ["email"=>$email]);
        }
        if (!$user->validateAsUserPassword($password)){
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
            ['role', 'string'],
            ['username', 'validateUsername'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        return PasswordValidator::validatePassword($this, $attribute, $params);
    }

    public function validateAsUserPassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->getHash());
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
     * @param string $token
     * @return ?User $user
     * 
     * @throws EntityNotFound 
    */
    public static function findUserByToken($token)
    {
        $token = AccessToken::findByToken($token);
        if (is_null($token))
        {
            throw EntityNotFound::entity('Token', ['token']);
        }
        $user = $token->getAttachedUser();
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
        return User::findOne([
            'username' => $username
        ]);
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
        $token = AccessToken::generateNewToken($this);
        $token->save();
        return $token->getToken();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        $token = AccessToken::findByToken($authKey);
        if (is_null($token))
        {
            return false;
        }
        return ($this->getId() === $token->getAttachedUser()->getId()) && ($token->getToken() === $authKey);
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

    public function getRole()
    {
        if (is_null($this->role))
        {
            return $this->getAttribute('role');
        }
        return $this->role;
    }

    public function isAdmin()
    {
        return $this->getRole() === User::ADMIN_ROLE;
    }

    public function getPassword()
    {
        if ($this->password === null)
        {
            return $this->getAttribute('password');
        }
        return $this->password;
    }

    public function getHash()
    {
        if ($this->hash === null)
        {
            return $this->getAttribute('hash');
        }
        return $this->hash;
    }

    public function getCreatedAt()
    {

    }

    public function getAttributes($names = null, $except = [])
    {
        $attributes = [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'hash' => $this->getHash(),
            'role' => $this->getRole(),
            'createdAt' => $this->getCreatedAt(),
        ];
        $result = [];
        if ($names === null)
        {
            $result = $attributes;
        } else {
            foreach ($names as $name) {
                $result[$name] = $attributes['name'];
            }
        }
        foreach ($except as $exceptAttribute) {
            unset($result[$exceptAttribute]);
        }
        return $result;
    }
}
