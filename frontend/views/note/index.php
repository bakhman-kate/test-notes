<?php

use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Notes');
$this->params['breadcrumbs'][] = $this->title;

if($button['id'] == 'vk-disconnect') {
    $this->registerJs('
        $(document).on("click", "#vk-disconnect", function(event) {
            event.preventDefault();
            
            $.ajax({
                url: $(this).attr("href"),
                method: "GET",
                dataType: "json"
            }).done(function(response) {
                window.location.href = "/note";                       
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("Произошла ошибка");
            });
        });
    ');    
}

$this->registerJs('
    $(document).on("click", ".delete-button", function(event) {
        event.preventDefault();
        var note = $(this).parent();
        
        $.ajax({
            url: $(this).attr("href"),
            method: "GET",
            dataType: "json"
        }).done(function(response) {
            if(response["note_id"]) {
                note.remove();
            }           
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert("Произошла ошибка");
        });
    });   
');?>

<div class="note-index">
    <h1 class='text-center'><?= Html::encode($this->title) ?></h1>
    
    <p><?= Html::a(Yii::t('app', 'Create Note'), ['create'], ['class' => 'btn btn-success']) ?></p>
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => 'Список заметок пуст',
        'emptyTextOptions' => [
            'tag' => 'p'
        ],
        'itemOptions' => ['class' => 'category-name'],
        'itemView' => function ($model, $key, $index, $widget) {            
            return $this->render('_list',[
                'model' => $model,
                'key' => $key, 
                'index' => $index, 
                'widget' => $widget
            ]);            
        },
        'layout' => "{items}\n{pager}",                
        'options' => [
            'class' => 'col-xs-12',
            'id' => 'notes-by-categories',
        ], 
        'pager' => [
            'class' => 'yii\widgets\LinkPager',
            'options' => ['class' => 'pagination pagination-sm'],
            'pagination' => new Pagination(['pageSize' => 10]),                        
        ],
    ]) ?>
    
    <?php
    
    if(is_array($vkNotesProvider->allModels)):?>
	<?= ListView::widget([
            'dataProvider' => $vkNotesProvider,
            'emptyText' => 'Заметки ВКонтакте отсутствуют',
            'emptyTextOptions' => [
                'tag' => 'p'
            ],
            'itemOptions' => ['class' => 'vk-note'],
            'itemView' => function ($model, $key, $index, $widget) {            
                return $this->render('_vk_list',[
                        'model' => $model,
                        'key' => $key, 
                        'index' => $index, 
                        'widget' => $widget
                ]);            
            },
            'layout' => "{items}\n{pager}",                
            'options' => [
                'class' => 'col-xs-12',
                'id' => 'vk-notes',
            ], 
            'pager' => [
                'class' => 'yii\widgets\LinkPager',
                'options' => ['class' => 'pagination pagination-sm'],
                'pagination' => new Pagination(['pageSize' => 10]),                        
            ],
	]) ?>
    <?php endif;?>
    
    <p class='text-center'><?= Html::a($button['text'], 
        $button['link'], 
        [
            'id' => $button["id"],
            'class' => "btn btn-default",
            'role' => "button"            
        ]
    )?></p>
</div>
