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
<div class="history-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
