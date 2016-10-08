<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\IdentityInterface;
use yii\web\Response;
use common\models\Note;
use yii\authclient\OAuthToken;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;   

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'UserName'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');        
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);         
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }   
    
    public function getCategories($filter=array())
    {
        $query = $this->hasMany(Category::className(), ['user_id' => 'id']);
        if(is_array($filter) && !empty($filter))
            $query->where($filter);
        return $query;
    }
    
    public function getCategoriesList()
    {
        $defaultCategoryName = Category::DEFAULT_CATEGORY_NAME;
        
        $data = $this->getCategories(['not', ['name' => $defaultCategoryName]])->all();
        if(!empty($data)) {
            foreach($data as $key => $row) {
                $name[$key]  = $row['name'];            
            }        
            array_multisort($name, SORT_NATURAL, $data);
        }        
        
        $data[] = $this->getCategories(['name' => $defaultCategoryName])->one();
        
        return $data;
    }
    
    public function getNotesByCondition($condition = [])
    {
        $query = Note::find()
            ->select(['note.id AS id', 'note.title AS title', 'note.category_id AS category_id', 'category.name AS category_name'])
            ->innerJoin('category', 'note.category_id = category.id')
            ->where(['category.user_id' => $this->id]);
        
        if(is_array($condition) && !empty($condition)) {
             $query->andWhere($condition);
        }
            
        $noteModels = new ActiveDataProvider(['query' => $query->asArray()]);        
        $notes = $noteModels->getModels();
        
        $categories = [];
        $titles = [];
        if(!empty($notes)) {
            foreach($notes as $key => $row) {
                $categories[$key]  = $row['category_name'];
                $titles[$key] = $row['title'];
            }        
            array_multisort($categories, SORT_NATURAL, $titles, SORT_NATURAL, $notes);
        }        
        
        return ArrayHelper::index($notes, 'id', [function ($element) {           
            return $element['category_name'];
        }, 'title']);
    }
    
    public function getNotesList()
    {
        $defaultCategoryName = Category::DEFAULT_CATEGORY_NAME;
        
        $categoryNotes = $this->getNotesByCondition(['not', ['category.name' => $defaultCategoryName]]);
        $withoutCategoryNotes = $this->getNotesByCondition(['category.name' => $defaultCategoryName]);
        
        return array_merge($categoryNotes, $withoutCategoryNotes);
    }
    
    public function getVkontakteNotes()
    {
        $notes = false;
        
        $vk = Yii::$app->authClientCollection->getClient('vkontakte');
        $oauthToken = new OAuthToken();
        $oauthToken->setToken($this->token);
        $vk->setAccessToken($oauthToken);
        $response = $vk->api('notes.get')['response'];
        
        if(is_array($response)) {
            $notes = [];
            foreach($response as $note) {
                if(is_array($note)){
                    $notes[] = $note;
                }
            }
        }
        
        return $notes;
    }
}
