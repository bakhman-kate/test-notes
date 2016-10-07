<?php
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;?>

<div><?= $key ?></div>
<?= ListView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model,
        'pagination' => false,            
    ]),
    'emptyText' => 'Список пуст',
    'emptyTextOptions' => [
        'tag' => 'p'
    ],
    'itemOptions' => ['class' => 'note-item', 'tag' => 'li'],
    'itemView' => function ($model, $key, $index, $widget) {
        $note = array_shift($model);  
        
        return Html::a(Html::encode($note['title']), ['show', 'id' => $note['id']])
            .' '.Html::a('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', 
                ['update', 'id' => $note['id']], 
                [
                    'class' => "btn btn-default btn-xs",
                    'role' => "button",
                    'title' => Yii::t('app', 'Update')
                ]
            )
            .' '.Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', 
                ['destroy', 'id' => $note['id'], 'ajax' => true], 
                [
                    'class' => "btn btn-default btn-xs delete-button",
                    'role' => "button",
                    'title' => Yii::t('app', 'Delete')
                ]
            );
    },
    'layout' => "{items}",                
    'options' => [
        'tag' => 'ul',
        'class' => 'col-xs-12',
        'id' => 'notes',
    ]
]) ?>