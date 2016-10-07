<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;?>

<div class="site-error">
    <?php switch($exception->statusCode)
    {
        case 404: $this->title = Yii::t('app','Error').' 404'; $alert_class = 'alert-warning'; $message = Yii::t('app', 'The requested page does not exist.'); break;
        case 403: $this->title = Yii::t('app','Error').' 403'; $message = Yii::t('app', 'You are not allowed to access this page.'); $alert_class = 'alert-info'; break;
        default: $this->title = $name;  $alert_class = 'alert-danger'; 
    }?>
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="alert <?=$alert_class?>">
        <?= nl2br(Html::encode($message)) ?>
    </div>    
</div>