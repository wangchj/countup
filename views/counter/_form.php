<?php
use \DateTimeZone;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\CounterAddAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Counter */
/* @var $form yii\widgets\ActiveForm */

CounterAddAsset::register($this);

?>

<?php
$timezones = [];
foreach(DateTimeZone::listIdentifiers() as $timezone) {
    $timezones[$timezone] = $timezone;
}
?>

<div class="counter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => 30]) ?>
    <?= $form->field($model, 'summary')->textarea() ?>
    <?= $form->field($model, 'startDate')->textInput() ?>
    <?= $form->field($model, 'timeZone')->dropDownList($timezones) ?>
    <?= $form->field($model, 'public')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Done', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
