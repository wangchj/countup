<?php
use yii\helpers\Url;
?>

<?php $this->beginContent('@app/views/layouts/main.php');?>

<style>
nav.vertical-menu {
    background-color:#fff;
    padding:15px 0px;
    font-weight:normal;
    color:#505050;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
    border-radius: 6px;
}

nav.vertical-menu a {
    display: block;
    padding-top:5px;
    padding-bottom:5px;
    padding-left:10px;
    padding-right:10px;
    cursor: pointer;
    color:#333;
    text-decoration: none;
    margin:0px;
}

/*nav.vertical-menu a:hover,*/ nav.vertical-menu a.active {
    background-color:#eee;
}

nav.vertical-menu hr {
    margin:5px;
}

.settings-content {
    background-color:#fff;
    padding:15px 15px;
    font-weight:normal;
    color:#505050;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
    border-radius: 6px;
}
</style>

<?php
    $action = $this->context->action->id;
?>

<div class="row">
    <div class="col-xs-3 col-xs-offset-1">
        <nav class="vertical-menu">
            <a href="<?=Url::to(['settings/index'])?>" class="<?=$action == 'index' ? 'active' : ''?>">Account Basics</a>
            <hr>
            <a href="<?=Url::to(['settings/password'])?>" class="<?=$action == 'password' ? 'active' : ''?>">Change Password</a>
            <hr>
            <a href="<?=Url::to(['settings/facebook'])?>" class="<?=$action == 'facebook' ? 'active' : ''?>">Facebook Integration</a>
        </nav>
    </div>
    <div class="col-xs-6">
        <div class="settings-content">
        <?=$content?>
        </div>
    </div>
</div>

<?php $this->endContent();?>