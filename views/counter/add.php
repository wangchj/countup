<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Add Counter';
$this->params['breadcrumbs'][] = ['label' => 'Counter', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="counter-add">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
