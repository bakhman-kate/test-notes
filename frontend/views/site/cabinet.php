<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\UpdateNameForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;?>

<div class="site-index">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    
    <div class="body-content">
        <div class="row">
            <div class="col-xs-12">
                <p><?= Html::a('Категории', Url::to('/category')) ?></p>
                <p><?= Html::a('Заметки', Url::to('/note')) ?></p>
            </div>
        </div>
    </div>    
</div>
