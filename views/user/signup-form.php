<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SignupAsset;
use app\models\User;

require_once(__DIR__ . '/../components/path.php');

SignupAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<center>
<div style="
    /*position:absolute;
    top:20%;
    left:30%;
    text-align:center*/">
    
    <div style="margin-bottom:20px;
        font-family:Helvetica Neue', Helvetica;
        font-size: 50px;
        font-weight:bold;
        color:#444;
        text-shadow: 1px 1px 3px #888">CountUp</div>

    <div style="width:400px;
        padding:40px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.298039) 0px 0px 8px 0px;
        color: rgb(33, 25, 34);
        display: block;
        font-family: 'Helvetica Neue', Helvetica, 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', メイリオ, Meiryo, 'ＭＳ Ｐゴシック', arial, sans-serif;
        font-size: 12px;">

        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'forename')->textInput() ?>
            <?= $form->field($model, 'surname')->textInput() ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'phash')->passwordInput() ?>
            <?= $form->field($model, 'gender')->dropDownList(User::getGenders()) ?>
       
            <div class="form-group">
                <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</center>
<style>
button.btn {
    font-family: 'Helvetica Neue', Helvetica, 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', メイリオ, Meiryo, 'ＭＳ Ｐゴシック', arial, sans-serif;
    font-size:15px;
    font-weight:bold;
    /*text-shadow: rgb(255, 255, 255) 0px 1px 0px;*/
    /*background-color: rgba(0, 0, 0, 0);
  background-image: linear-gradient(rgb(41, 83, 173), rgb(35, 76, 162));*/
}
</style>