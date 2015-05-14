<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\SignupAsset;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

SignupAsset::register($this);
?>

<script>
  // This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);

    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        console.log('Logged into your app and Facebook.');
        testAPI();
    }
    else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        console.log('Logged into Facebook but not this app');
    }
    else {
        // The person is not logged into Facebook, so we're not sure if they are logged into this app or not.
        console.log('Not logged into Facebook');
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
//function checkLoginState() {
//    FB.getLoginStatus(function(response) {
//        statusChangeCallback(response);
//    });
//}
function checkLoginState() {
    FB.getLoginStatus(statusChangeCallback);
}

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
            FB.api('/me', function(response) {
                $('#user-fbId').val(response.id);
                $('#user-forename').val(response.first_name);
                $('#user-surname').val(response.last_name);
                $('#user-gender').val(response.gender);
                if(response.email && response.email != '')
                    $('#user-email').val(response.email);
                if(response.location) {
                    $('#user-location').val(response.location.name);
                    setTimezone(response.location.name);
                }
                
                FB.api('/me/picture', {type:'square',width:'200',fields:'url'}, function(photo_res) {
                    //console.log(photo_res);
                    $('#user-picture').val(photo_res.data.url);
                });

                $('#w0').submit();
            });
        }
    }, {scope: 'email,user_location'});
}

var geocoder;

function setTimezone(cityState) {
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({address:cityState}, function(res, status) {
        if(status == google.maps.GeocoderStatus.OK) {
            $.ajax({
                type : 'get',
                url  : 'https://maps.googleapis.com/maps/api/timezone/json?location=' + res[0].geometry.location.toUrlValue() + '&timestamp=-1',
                success: function(data) {
                    console.log('Timezone translation success');
                    console.log(data);
                    $('#user-timezone').val(data.timeZoneId);
                    //$('#w0').submit();
                },
                error: function() {
                    console.log('Timezone translation error');
                }
            });
        }
        else {
            console.log('Geocoding not ok.');
        }
    });
    
}

window.fbAsyncInit = function() {
    FB.init({
        appId      : '684659218326813',
        cookie     : true,  // enable cookies to allow the server to access the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.3' // use version 2.3
    });

    // Now that we've initialized the JavaScript SDK, we call 
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    //FB.getLoginStatus(statusChangeCallback);

};

// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
    FB.api('/me', function(response) {
        console.log('Successful login for: ' + response.name);
    });
}

</script>

<!-- 
<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>
-->

<div style="
    position:absolute;
    top:20%;
    left:30%;
    text-align:center">
    
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

        <button class="btn btn-primary btn-lg btn-block" type="button" onClick="fbLogin()">
            <span class="buttonText">Sign up with Facebook</span>
        </button>

        <br />

        <button class="btn btn-default btn-lg btn-block" type="button" onclick="window.location.href='<?=Url::to(['user/signup-form'])?>'">
            <span class="buttonText">Sign up with Email</span>
        </button>

        <?php if($error):?>
        <br />
        <div style="color:#cb2027;"><?=$error?></div>
        <?php endif;?>
    </div>
</div>

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

<?php $form = ActiveForm::begin(); ?>
    <input type="hidden" id="user-fbId" name="User[fbId]">
    <input type="hidden" id="user-forename" name="User[forename]">
    <input type="hidden" id="user-surname" name="User[surname]">
    <input type="hidden" id="user-gender" name="User[gender]">
    <input type="hidden" id="user-email" name="User[email]">
    <input type="hidden" id="user-location" name="User[location]">
    <input type="hidden" id="user-timezone" name="User[timezone]">
    <input type="hidden" id="user-picture" name="User[picture]">
<?php ActiveForm::end(); ?>