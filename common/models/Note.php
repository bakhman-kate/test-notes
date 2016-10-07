<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "note".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $category_id
 *
 * @property Category $category
 */
class Note extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'note';
    }

    /**
     * @inheritdoc    
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['title'], 'string', 'min' => 4, 'max' => 100],
            [['text'], 'string', 'max' => 1024],             
            [['category_id'], 'integer'],            
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
