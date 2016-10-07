<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Note */

$this->title = Yii::t('app', 'Update Note: ').$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['show', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');?>

<div class="note-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
