<?php

namespace common\models;

use Yii;
use common\models\Note;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $user_id
 *
 * @property User $user
 */
class Category extends \yii\db\ActiveRecord
{
    const DEFAULT_CATEGORY_NAME = 'Без категории';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc  
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'min' => 4, 'max' => 30],
            [['description'], 'string', 'max' => 200],
            [['name', 'user_id'], 'unique', 'targetAttribute' => ['name', 'user_id'], 'message' => Yii::t('app', 'The combination of Name and User ID has already been taken.')],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getNotes()
    {
        return $this->hasMany(Note::className(), ['category_id' => 'id']);
    }
}
