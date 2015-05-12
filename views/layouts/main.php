<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\NavAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
NavAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <!-- meta name="viewport" content="width=device-width, initial-scale=1" -->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <style>
        body {
            background-color: #e9e9e9;
        }

        h1 {
            font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
            font-size: 20px;
            font-weight:bold;
            color:#505050;
            margin-top:0px;
        }
    </style>

</head>
<body>

<?php $this->beginBody() ?>
<?php $expandHeader = (Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id == 'index')?>

<div id="header-wrap" style="
    /*border:1px solid black;*/
    box-shadow:0px 1px 5px #808080;
    padding-top:0px;
    padding-bottom:0px;
    margin-bottom:20px;
    background-color:#fff">
    
    <nav id="w1" class="navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=Url::to(['site/index'])?>">CountUp</a>
            </div>
            <div id="w1-collapse" class="collapse navbar-collapse">
                <ul id="w2" class="navbar-nav navbar-right nav">
                    <li><a id="add-counter" href="#">Add Counter</a></li>
                    <li><a href="<?=Url::to(['user/settings'])?>">Settings</a></li>
                    <li><a href="<?=Url::to(['site/logout'])?>" data-method="post">Logout</a></li>
                    <li>
                        <?php if(Yii::$app->user->identity->picture): ?>
                            <img src="<?=Yii::$app->user->identity->getPicture()?>"
                                title="Logged in as <?=Yii::$app->user->identity->forename?>"
                                style="width:40px; margin-top:6px"
                                class="img-circle">
                        <?php else: ?>
                            <img src="<?=Yii::$app->user->identity->getPicture()?>"
                                title="Logged in as <?=Yii::$app->user->identity->forename?>"
                                style="width:40px; margin-top:6px; border-width:2px; border-color:#eee"
                                class="img-circle img-thumbnail">
                        <?php endif;?>
                    </li>
                </ul>
            </div>
        </div> <!-- container -->
    </nav>

    <?php if($expandHeader):?>
        <?php 
        $viewer = $this->params['viewer'];
        $viewee = $this->params['viewee'];
        ?>

        <hr style="margin-top:0px;margin-bottom:10px">

        <div class="container">
            <div class="row">
                <div class="col-xs-12" style="text-align:center">
                    <h1>
                        <?php if($viewer->picture): ?>
                            <img src="<?=$viewee->getPicture()?>" style="width:50px; margin-top:6px" class="img-circle">
                        <?php else: ?>
                            <img src="<?=$viewee->getPicture()?>" style="width:50px; margin-top:6px; border-width:3px; border-color:#eee" class="img-circle img-thumbnail">
                        <?php endif;?>
                        <?="$viewee->forename $viewee->surname"?>
                        <?php if($viewer->userId != $viewee->userId):?>
                            <?php if($viewer->isFollowerOf($viewee->userId)):?>
                                <button class="btn btn-default btn-unfollow pull-right" user-id="<?=$viewee->userId?>" style="">Unfollow</button>
                            <?php else:?>
                                <button class="btn btn-default btn-follow pull-right" user-id="<?=$viewee->userId?>" style="margin-top:12px">Follow</button>
                            <?php endif;?>
                        <?php endif;?>
                    </h1>    
                </div>
            </div><!-- row -->
        </div><!-- container -->
    <?php endif;?>
</div>

<div class="container">
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <!-- p class="pull-left">&copy; Countup <?= date('Y') ?></p -->
        <!-- p class="pull-right"><?= Yii::powered() ?></p -->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
