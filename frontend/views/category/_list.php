<?php
use yii\helpers\Html;?>

<?= Html::a(Html::encode($model['name']), ['show', 'id' => $model['id']]) ?>

<?= Html::a('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', 
    ['update', 'id' => $model['id']], 
    [
        'class' => "btn btn-default btn-xs",
        'role' => "button",
        'title' => Yii::t('app', 'Update')
    ]
)?>

<?= Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', 
    ['destroy', 'id' => $model['id'], 'ajax' => true], 
    [
        'class' => "btn btn-default btn-xs delete-button",
        'role' => "button",
        'title' => Yii::t('app', 'Delete')
    ]
)?>