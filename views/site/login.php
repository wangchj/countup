<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */
?>

<script>
// Load Facebook SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function() {
    FB.init({
        appId      : '684659218326813',
        cookie     : true,  // enable cookies to allow the server to access the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.3' // use version 2.3
    });
};

/**
 * Facebook login response object:
 *
 * Object {
 *     email: "cjw39@hotmail.com"
 *     first_name: "Jeff"
 *     gender: "male"
 *     id: "10102879698662661"
 *     last_name: "Wang"
 *     link: "https://www.facebook.com/app_scoped_user_id/10102879698662661/"
 *     locale: "en_US"
 *     location: Object {
 *         id: "108417995849344"
 *         name: "Auburn, Alabama"
 *     }
 *     name: "Jeff Wang"
 *     timezone: -5
 *     updated_time: "2013-10-10T02:52:01+0000"
 *     verified: true
 * }
 */
function fbLogin() {
    FB.login(function(response) {
        if(response.status == 'connected') {
            console.log('Facebook login connected.');
            FB.api('/me', function(response) {
                $('#fbId').val(response.id);
                $('#login-form').submit();
            });
        }
    });
}
</script>

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

    <div style="width:350px;
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

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div>{input}</div>\n<div>{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

    <input type="hidden" id="fbId" name="fbId">

    <button class="btn btn-primary btn-lg btn-block" type="button" onClick="fbLogin()">
            <span class="buttonText">Log In with Facebook</span>
    </button>

    <?php if(isset($error) && $error): ?>
    <br />
    <span style="color:#cb2027;"><?=$error?></span>
    <?php endif;?>

    <hr style="margin-top:30px" />

    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
    </div>
    </center>
