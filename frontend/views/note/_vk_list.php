<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;?>

<div class='vk-note-name'><?= Html::a(Html::encode($model['title']), $model['view_url'], ['target' => '_blank']) ?></div>
<div class='vk-note-id'>ID: <?= $model['id']?></div>
<div class='vk-note-date'><?= date("d.m.Y H:i", $model['date'])?></div>
<div class='vk-note-text'><?= HtmlPurifier::process($model['text'])?></div>