<?php

use yii\helpers\Url;
use yii\web\AssetBundle;
use app\lib\DateTime;

//use app\views\site\HomeAsset;

class HomeAsset extends AssetBundle
{
    public $sourcePath = '@app/views/site';
    public $css = [
        //'home.css',
        'cal.css'
    ];
    public $js = ['home.js'];
    public $depends = [
        'app\assets\SnapsvgAsset',
        'yii\web\JqueryAsset',
    ];
}

HomeAsset::register($this);

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

body {
    background-color: #e9e9e9;
}

nav.navbar {
    margin-top:0px;
    margin-bottom:0px;
    background-color: #fff;
    border-color: #fff;
    padding-top:10px;
    padding-bottom:10px;
}
</style>

<div id="header-wrap" style="
    /*border:1px solid black;*/
    box-shadow:0px 1px 5px #808080;
    padding-top:0px;
    padding-bottom:0px;
    margin-bottom:20px">
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
                    <li><a href="<?=Url::to(['counter/add'])?>" data-method="post">Add Counter</a></li>
                    <li><a href="<?=Url::to(['user/settings'])?>" data-method="post">Settings</a></li>
                    <li><a href="<?=Url::to(['site/logout'])?>" data-method="post">Logout</a></li>
                    <li>
                        <img src="<?=Yii::$app->user->identity->picture?>"
                            title="Logged in as <?=Yii::$app->user->identity->forename?>"
                            style="width:40px; margin-top:6px"
                            class="img-circle">
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container">
    
<?php if(!Yii::$app->user->identity->counters): ?>
    <div class="row" style="
        /*border:1px solid gray;*/
        background-color:#fff;
        padding:20px;
        font-size:18px;
        font-weight:bold;
        color:#808080;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
        border-radius: 6px;">
        <div class="col-xs-12" style="text-align:center;">
            You currently have no counters. Click on Add Counter to add one!
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach(Yii::$app->user->identity->counters as $counter):?>
            <div class="col-sm-5" style="padding-top:15px; padding-bottom:15px"> <!-- Produce gutters between counters -->
                <div style="
                    /*border:1px solid gray;*/
                    background-color:#fff;
                    padding:15px 20px;
                    font-size:18px;
                    font-weight:bold;
                    color:#505050;
                    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
                    border-radius: 6px;
                    /*margin-bottom:20px;*/
                    text-align:center;
                    "> <!-- Counter panel with pretty border and round corners. -->
                    <div class="row clabel">
                        <div class="col-xs-12" style=""><?=$counter->label?></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <div style="font-size:10px; font-weight:normal; color:#999; margin-bottom:5px">Current Count</div>
                            <div style="margin-bottom:8px"><?=$counter->getDays()?> Days</div>
                            <div style="font-size:10px; font-weight:normal; color:#ccc">
                                 <?= $counter->isActive() ? 'since ' . $counter->getCurrentStartDate()->format('F j, Y') : 'not running'?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div style="font-size:10px; font-weight:normal; color:#999; margin-bottom:5px">Best</div>
                            <?=$counter->getBest()?> Days
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-12 cimg">
                            <svg id="cal<?=$counter->counterId?>"></svg>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
<?php endif;?>
</div>

<script>
var data = <?=json_encode($data)?>;
for(var cal in data) {
    var hist = data[cal];
    for(var i = 0; i < hist.length; i++) {
        hist[i]['start'] = new Date(hist[i]['start']);
        hist[i]['start'].setDate(hist[i]['start'].getDate() + 1);
        if(hist[i]['end'] != null) {
            hist[i]['end'] = new Date(hist[i]['end']);
            hist[i]['end'].setDate(hist[i]['end'].getDate() + 1);
        }
    }
}
</script>
