<?php

use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    $(document).on("click", ".delete-button", function(event) {
        event.preventDefault();
        var category = $(this).parent();
        
        $.ajax({
            url: $(this).attr("href"),
            method: "GET",
            dataType: "json"
        }).done(function(response) {
            if(response["category_id"]) {
                category.remove();
            }           
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert("Произошла ошибка");
        });
    });    
');?>

<div class="category-index">
    <h1 class='text-center'><?= Html::encode($this->title) ?></h1>
    
    <p><?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?></p>
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => 'Список пуст',
        'emptyTextOptions' => [
            'tag' => 'p'
        ],
        'itemOptions' => ['class' => 'category-item'],
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
            'tag' => 'div',
            'class' => 'col-xs-12',
            'id' => 'categories',
        ], 
        'pager' => [
            'class' => 'yii\widgets\LinkPager',
            'options' => ['class' => 'pagination pagination-sm'],
            'pagination' => new Pagination(['pageSize' => 10]),                        
        ],
    ]) ?>
</div>
