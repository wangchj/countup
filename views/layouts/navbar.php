<?php
use yii\helpers\Url;

if(!isset($bigHeader)) 
    $bigHeader = false;
?>

<style>
.navbar .nav li a {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 16px;
    font-weight:bold;
    color:#505050;
    /*opacity:0.5;*/
    /*text-shadow:1px 1px 8px #AAA;*/
}

.navbar .nav li a:hover {
    background-color:transparent;
}

.navbar a.navbar-brand {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 24px;
    font-weight:bold;
    color:#505050;
    /*opacity:0.5;*/
    /*text-shadow:-1px -1px 0px #AAA;*/
}
nav.navbar {
    margin-top:0px;
    margin-bottom:0px;
    background-color: #fff;
    border-color: #fff;
    padding-top:10px;
    <?php if(!$bigHeader):?>
    padding-bottom:10px;
    <?php endif;?>
}
</style>

<nav id="w1" class="navbar" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">CountUp</a>
        </div>
        <div id="w1-collapse" class="collapse navbar-collapse">
            <ul id="w2" class="navbar-nav navbar-right nav">
                <li><a href="<?=Url::to(['counter/add'])?>">Add Counter</a></li>
                <li><a href="<?=Url::to(['user/settings'])?>">Settings</a></li>
                <li><a href="<?=Url::to(['site/logout'])?>">Logout</a></li>
                <li>
                    <img src="<?=Yii::$app->user->identity->getPicture()?>"
                        title="Logged in as <?=Yii::$app->user->identity->forename?>"
                        style="width:40px; margin-top:6px"
                        class="img-circle">
                </li>
            </ul>
        </div>
    </div> <!-- container -->
</nav>