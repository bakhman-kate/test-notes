<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\UpdateNameForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    $(document).on("click", "#vk-connect", function(event) { 
        //alert("Попытка подключиться вконтакте");
        //$(this).text("Отключить ВКонтакте");
    });
');?>

<div class="site-index">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    
    <div class="body-content">
        <div class="row">
            <div class="col-xs-12">
                <p><?= Html::a('Категории', Url::to('/category')) ?></p>
                <p><?= Html::a('Заметки', Url::to('/note')) ?></p> 
                
                <p class='text-center'><?= Html::button(Yii::t('app', 'VK connect'), [
                    'id' => 'vk-connect',
                    'class' => "btn btn-default",
                    'type' => "button"]
                )?></p>
            </div>
        </div>
    </div>    
</div>
