<?php

use common\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Note */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="note-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
    
    <?php $items = ArrayHelper::map(Category::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'name');?>    
    <?= $form->field($model, 'category_id')->dropdownList($items,
        [
            //'prompt' => Yii::t('app', 'Select category'),
            'options' => [
                array_shift(array_keys($items)) => ['selected' => true]
            ]           
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
