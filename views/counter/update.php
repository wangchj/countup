<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Counter */

$this->title = $model->label;
?>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9">
        <img src="http://www.k2g2.org/lib/plugins/avatar/stitchy/stitchy.php?seed=<?=md5($model->user->userName)?>&size=50&.png" />
        <span style="font-size:36px;margin-left:15px;vertical-align:middle"><?=$model->user->userName?></span>
    </div>
</div>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9"><h2>Update '<?=Html::encode($model->label)?>'</h2></div>
</div>

<div class="row" style="margin-top:20px">
    <div class="col-xs-3">
        <ul class="list-group">
            <a href="<?=Url::to(['counter/view', 'username'=>$model->user->userName, 'id'=>$model->counterId])?>" class="list-group-item">Summary</a>
            <!-- a href="<?=Url::to(['counter/update','id'=>$model->counterId])?>" class="list-group-item active">Update</a -->
        </ul>
    </div>
    <div class="col-xs-9">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

