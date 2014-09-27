<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Counter */

$this->title = $model->label;
?>
<div class="counter-update">

    <div class="row">
        <div class="col-xs-2" style="margin-top:75px">
            <ul class="list-group">
                <a href="<?=Url::to(['counter/view','id'=>$model->counterId])?>" class="list-group-item">Summary</a>
                <a href="#" class="list-group-item">History</a>
                <a href="<?=Url::to(['counter/update','id'=>$model->counterId])?>" class="list-group-item active">Update</a>
            </ul>
        </div>
        <div class="col-xs-10">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
