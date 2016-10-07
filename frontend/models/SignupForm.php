<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Auth;
use common\models\User;
use common\models\Category;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'username' => Yii::t('app', 'UserName')
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //$user->generatePasswordResetToken();
        
        if($user->save()) {
            $category = new Category();            
            $category->name = 'Без категории';
            $category->user_id = $user->id;
            $category->save();
            
            /*$auth = new Auth([
                'user_id' => $user->id,
                'source' => $client->getId(),
                'source_id' => (string)$attributes['id'],
            ]);
            $auth->save();*/
            
            return $user;
        }
                
        return null;
    }
}
