<?php
use app\models\User;
use yii\helpers\Url;
use yii\web\AssetBundle;
?>

<?php
class SetPasswordAsset extends AssetBundle {
    public $sourcePath = '@app/views/settings';
    public $js = ['password.js'];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
SetPasswordAsset::register($this);
?>

<?php
$user = Yii::$app->user->identity;
?>

<h1>Change Password</h1>

<hr>

<div id="note" style="display:none"></div>

<form id="change-password-form">
    <input type="hidden" id="user-userId" name="userId" value="<?=$user->userId?>">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="old-password">Current Password</label>
                <input type="password" class="form-control" id="old-password" name="old-password">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="new-password">New Password</label>
                <input type="password" class="form-control" id="new-password" name="new-password">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <button id="preview-btn" class="btn btn-default">Preview</button>
            </div>
        </div>
    </div>

    <hr style="margin-top:10px">

    <div class="row">
        <div class="col-xs-12">
            <button id="save-btn" class="btn btn-primary pull-right">Save</button>
        </div>
    </div>
</form>

<script>
var changePasswordUrl = '<?=Url::to(['user/change-password'])?>';
</script>
