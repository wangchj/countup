<?php

use app\models\Counter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\views\components\ViewUtil;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'History';
?>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9">
    <img src="http://www.k2g2.org/lib/plugins/avatar/stitchy/stitchy.php?seed=<?=md5($owner->userName)?>&size=50&.png" />
    <span style="font-size:36px;margin-left:15px;vertical-align:middle"><?=$owner->userName?></span>
</div></div>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9"><h2 style="margin-bottom:20px">History <?php if($counterLabel != null):?>of '<?=Html::encode($counterLabel)?>'<?php endif;?></h2></div>
</div>

<div class="row"">
    <div class="col-xs-3">
        <ul class="list-group">
            <a href="<?=Url::to(['counter/index', 'username'=>$owner->userName])?>" class="list-group-item"><?=$owner->userName?>'s Profile</a>
            <!-- a href="<?=Url::to(['history/index', 'username'=>$owner->userName])?>" class="list-group-item active">History</a -->
        </ul>
    </div>

    <div class="col-xs-9">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'=>'Counter',
                    'content'=>function($data){
                        $url = Url::to(['counter/view', 'id'=>$data->counterId]);
                        return "<a href=\"$url\">{$data->counter->label}</a>";
                    }
                ],
                'startDate',
                'endDate',
                [
                    'label'=>'Day Count',
                    'value'=> function ($data){
                        return ViewUtil::getCountStr(Counter::computeDateIntervalBase($data->startDate, $data['endDate']));
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{delete}',
                    'visible'=> !Yii::$app->user->isGuest && Yii::$app->user->id == $owner->userId,
                ],
            ],
        ]); ?>
    </div>
</div>
