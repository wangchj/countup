<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
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
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <nav id="w0" class="navbar-inverse navbar-fixed-top navbar" role="navigation">
            <div class="container" style="width:970px !important">
                <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/countup/index.php">Countup</a>
                </div>
                <div id="w0-collapse" class="collapse navbar-collapse">
                    <ul id="w1" class="navbar-nav navbar-right nav">
                    <?php if(!Yii::$app->user->isGuest): ?>
                        <li><a href="/countup/index.php/site/contact">Settings</a></li>
                    <?php endif;?>
                    <?php if(Yii::$app->user->isGuest): ?>
                        <li><a href="<?= Url::to(['site/login'])?>" data-method="post">Login</a></li>
                    <?php else: ?>
                        <li><a href="<?= Url::to(['site/logout'])?>" data-method="post">Logout (<?= Yii::$app->user->identity->userName ?>)</a></li>
                    <?php endif;?>
                    </ul>
                </div>
            </div>
        </nav>

        <?php /*
            NavBar::begin([
                'brandLabel' => 'Countup',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'About', 'url' => ['/site/about']],
                    ['label' => 'Contact', 'url' => ['/site/contact']],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/site/login']] :
                        ['label' => 'Logout (' . Yii::$app->user->identity->userName . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ],
                ],
            ]);
            NavBar::end(); */
        ?>

        <div class="container" style="width:970px !important">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
