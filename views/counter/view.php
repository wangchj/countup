<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Counter */

$this->title = $model->label;
?>

<div class="counter-view">
    <div class="row">
        <div class="col-xs-3" style="margin-top:69px">
            <ul class="list-group">
                <a href="<?=Url::to(['counter/view','id'=>$model->counterId])?>" class="list-group-item active">Summary</a>
                <a href="<?=Url::to(['history/index','username'=>$model->user->userName, 'counterId'=>$model->counterId])?>" class="list-group-item">History</a>
                <a href="<?=Url::to(['counter/update','id'=>$model->counterId])?>" class="list-group-item">Update</a>
            </ul>

            <ul class="list-group">
                <a href="<?=Url::to(['counter/reset','id'=>$model->counterId])?>" class="list-group-item">Reset <span class="badge">!</span></a>
                <a href="<?=Url::to(['counter/deactivate','id'=>$model->counterId])?>" class="list-group-item">Deactivate</a>
            </ul>
        </div>    
        <div class="col-xs-9">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    ['label'=>'Owner', 'value'=>$model->user->userName],
                    'label',
                    'summary',
                    'startDate:date',
                    'public:boolean',
                    'active:boolean',
                ],
            ]) ?>
        </div>
</div>
