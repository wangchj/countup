<?php
use app\models\User;
use yii\helpers\Url;
use yii\web\AssetBundle;
?>

<?php
class SetBasicsAsset extends AssetBundle {
    public $sourcePath = '@app/views/settings';
    public $js = ['basics.js'];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\BlueimpFileUploadAsset'
    ];
}
SetBasicsAsset::register($this);
?>

<?php
$user = Yii::$app->user->identity;
?>

<h1>Account Basics</h1>

<hr>

<div id="note" style="display:none"></div>

<form id="account-basics-form">
    <input type="hidden" id="user-userId" name="User[userId]" value="<?=$user->userId?>">

    <div class="form-group">
        <label class="control-label">Profile Picture</label>
        <div style="margin-top:10px">
            <img id="user-picture" src="<?=$user->getPicture()?>" class="img-circle"
                style="width:60px; margin-right:20px"
            >
            <span class="btn btn-default fileinput-button">
                <span>Change Picture</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="picture">
            </span>

        <!-- button id="change-pic-btn" class="btn btn-default">Change Picture</button -->  
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-forename">First Name</label>
                <input type="text" class="form-control" id="user-forename" name="User[forename]" value="<?=$user->forename?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-surname">Last Name</label>
                <input type="text" class="form-control" id="user-surname" name="User[surname]" value="<?=$user->surname?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-email">Email</label>
                <input type="text" class="form-control" id="user-email" name="User[email]"  value="<?=$user->email?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-username">User Name</label>
                <input type="text" class="form-control" id="user-username" name="User[userName]"  value="<?=$user->userName?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-timezone">Time Zone</label>
                <select id="user-timezone" class="form-control" name="User[timeZone]">
                    <?php foreach(DateTimeZone::listIdentifiers() as $timezone):?>
                        <option value="<?=$timezone?>" <?=$user->timeZone == $timezone ? 'selected' : ''?>><?=$timezone?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-gender">Gender</label>
                <select id="user-gender" class="form-control" name="User[gender]">
                    <?php foreach(User::getGenders() as $key=>$val):?>
                        <option value="<?=$key?>" <?=$user->gender==$key ? 'selected' : ''?>><?=$val?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>

</form>

<hr style="margin-top:10px">

<div class="row">
    <div class="col-xs-12">
        <button id="save-btn" class="btn btn-primary pull-right">Save</button>
    </div>
</div>

<script>
var userUpdateUrl = '<?=Url::to(['user/update'])?>';
var userPictureUploadUrl = '<?=Url::to(['user/upload-picture'])?>';
var userPictureMaxSize = <?=Yii::$app->params['userPicture']['maxSize']?>;
</script>
