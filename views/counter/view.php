<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\Counter;
use app\views\components\ViewUtil;

/* @var $this yii\web\View */
/* @var $model app\models\Counter */

$this->title = $model->label;
?>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9">
    <img src="http://www.k2g2.org/lib/plugins/avatar/stitchy/stitchy.php?seed=<?=md5($model->user->userName)?>&size=50&.png" />
    <span style="font-size:36px;margin-left:15px;vertical-align:middle"><a style="color:#000;text-decoration:none" href="<?=Url::to(['counter/index', 'username'=>$model->user->userName])?>"><?=$model->user->userName?></a></span>
</div></div>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9"><h2><?= Html::encode($this->title) ?></h2></div>
</div>


<div class="row" style="margin-top:20px">
    <div class="col-xs-3">
        <ul class="list-group">
            <!-- a href="<?=Url::to(['counter/view','id'=>$model->counterId])?>" class="list-group-item active">Summary</a -->
            <a href="<?=Url::to(['history/index','username'=>$model->user->userName, 'counterId'=>$model->counterId])?>" class="list-group-item">History</a>
        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->userId):?>
            <a href="<?=Url::to(['counter/update','id'=>$model->counterId])?>" class="list-group-item">Update</a>
            <a href="<?=Url::to(['counter/reset','id'=>$model->counterId])?>" class="list-group-item">Reset <span class="badge">!</span></a>
            <a href="<?=Url::to(['counter/deactivate','id'=>$model->counterId])?>" class="list-group-item">Deactivate</a>
        <?php endif;?>
        </ul>
    </div>    
    <div class="col-xs-9">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //['label'=>'Owner', 'value'=>$model->user->userName,'visible'=>false],
                //'label',
                'summary',
                'startDate:date',
                
                [//Day count field
                    'label'=>'Count',
                    'value'=>Counter::computeDateInterval($model)->days . ' days'
                ],
                
                [//Privacy field
                    'attribute'=>'public',
                    'format'=>'boolean',
                    'visible'=>(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->userId)
                ],

                [//Active field
                    'attribute'=>'active',
                    'format'=>'boolean',
                    'visible'=>(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->userId)

                ],
            ],
        ]) ?>
    </div>
