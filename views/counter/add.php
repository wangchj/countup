<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Add Counter - CountUp';
echo $this->render('@app/views/layouts/header-small.php');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-xs-12 col-sm-offset-4">
            <div style="background-color:#fff;
                width:500px;
                padding:20px;
                font-family: 'Helvetica Neue', Helvetica, 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', メイリオ, Meiryo, 'ＭＳ Ｐゴシック', arial, sans-serif;
                color: #000; /*rgb(33, 25, 34);*/
                font-size: 12px;
                font-weight:bold;
                /*color:#808080;*/
                /*box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);*/
                box-shadow: 2px 2px 6px #808080;
                border-radius: 6px;
                text-align:center;
                margin-top:20px">

                <h1 style="margin-bottom:30px">Add Counter</h1>

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
