<?php

use yii\helpers\Url;
use yii\web\AssetBundle;
use app\lib\DateTime;

class Asset extends AssetBundle {
    public $sourcePath = '@app/views/user';
    public $js = ['cal.js', 'index.js'];
    public $css = ['cal.css'];
    public $depends = [
        'app\assets\SnapsvgAsset',
        'yii\web\JqueryAsset',
    ];
}
Asset::register($this);
?>

<style>
.friendly {
    height:100px;
}

h2.friendlys {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 16px;
    font-weight:bold;
    color:#505050;
    text-shadow: 0px 1px 1px #fff;
    text-align: center;
    margin-bottom: 20px;
}

.friendly-name {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 11px;
    font-weight:bold;
    color:#505050;
    margin-top:0px;
    text-align: center;
}

.cslink {
    display: block;
    padding: 5px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
    list-style-type: none;
    list-style: none;
    text-decoration: none;
    text-shadow: 0px 1px 1px #fff;
}

.cslink:hover{
    color:#333;
    text-decoration: none;
}

.csli {
    list-style-type: none;"
    margin:0px;
}

.csli:hover{
    background-color: #eee;
}

.popover-content {
    padding-left:0px;
    padding-right:0px;
}

a.counter-setting, a.counter-setting:hover, a.counter-setting:active {
    font-size:14px;
    color:#505050;
    text-shadow: 0px 1px 1px #fff;
    text-decoration: none;
}

</style>

<?php echo $this->render('@app/views/layouts/header-big.php', ['viewer'=>$viewer, 'viewee'=>$viewee]);?>

<div class="container">

<?php if(count($counters) == 0): ?>
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
            <?php if($viewer->userId == $viewee->userId) : ?>
                You currently have no counters. Click on Add Counter to add one!
            <?php else: ?>
                <?=$viewee->forename?> currently have no counters.
            <?php endif;?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-10 col-sm-12"> <!-- Container for counters -->
            <div class="row">
                <?php foreach($counters as $counter):?>
                    <div class="col-sm-6" style="padding-top:15px; padding-bottom:15px"> <!-- Produce gutters between counters -->
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
                                <div class="col-xs-12" style="">
                                    <?=$counter->label?>
                                    <div class="pull-right">
                                            <a class="counter-setting" href="#" id="cs<?=$counter->counterId?>">
                                                <span class="glyphicon glyphicon-cog"></span>
                                            </a>

                                            <ul class="dropdown-menu" role="menu">
                                                <li class="csli"><a class="cslink cs-reset" data-counterId="<?=$counter->counterId?>" onclick="resetClicked(<?=$counter->counterId?>)" href="#"><span class="glyphicon glyphicon-flash"></span> Reset Counter</a></li>
                                                <li class="csli"><a class="cslink cs-stop" data-counterId="<?=$counter->counterId?>" href="#"><span class="glyphicon glyphicon-stop"></span> Stop Counter</a></li>
                                                <hr style="margin:4px">
                                                <li class="csli"><a class="cslink cs-remove" data-counterId="<?=$counter->counterId?>" href="#"><span class="glyphicon glyphicon-fire"></span> Remove Counter</a></li>
                                                <hr style="margin:4px">
                                                <li class="csli"><a class="cslink cs-settings" data-counterId="<?=$counter->counterId?>" href="#"><span class="glyphicon glyphicon-cog"></span> Counter Settings</a></li>
                                            </ul>

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px">Current Count</div>
                                    <div style="margin-bottom:8px"><?=$counter->getDays()?> Days</div>
                                    <div style="font-size:12px; font-weight:normal; color:#ccc">
                                         <?= $counter->isActive() ? 'since ' . $counter->getCurrentStartDate()->format('M j, Y') : 'not running'?>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px">Best</div>
                                    <div style="margin-bottom:8px"><?php $best = $counter->getBest(); ?> <?=$best['count']?> Days</div>
                                    <div style="font-size:12px; font-weight:normal; color:#ccc">
                                        <?php if($best['startDate'] && $best['endDate']):?>
                                            <?= $best['startDate']->format('M j, Y') ?> - <?= $best['endDate']->format('M j, Y') ?>
                                        <?php else:?>
                                            &nbsp; 
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-6" style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px;padding-left:50px">
                                            <?= (new DateTime())->setTimestamp(strtotime('first day of last month'))->format('F');?>
                                        </div>
                                        <div class="col-xs-6" style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px;padding-right:50px">
                                            <?= (new DateTime())->format('F');?>
                                        </div>
                                    </div> <!-- row -->
                                    <div class="row">
                                        <div class="col-xs-12 cimg">
                                            <svg id="cal<?=$counter->counterId?>"></svg>
                                        </div>
                                    </div> <!-- row -->
                                </div>
                            </div> <!-- row -->
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-sm-2 hidden-sm">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="friendlys">Friendly's</h2>
                </div>
            </div>
            <div class="row">
                <?php
                $pics = [
                    ['name'=>'Ana Ivanovic', 'pic'=>'ai.jpg'], 
                    ['name'=>'Ivy Chung', 'pic'=>'ic.jpg'],
                    ['name'=>'Kei Nishikori', 'pic'=>'kn.jpg'],
                    ['name'=>'Maria Sharapova', 'pic'=>'ms.jpg'],
                    ['name'=>' Pete Sampras', 'pic'=>'ps.jpg'],
                    ['name'=>'Rafael Nadal', 'pic'=>'rn.jpg'],
                    ['name'=>'Eugenie Bouchard', 'pic'=>'gb.jpg'],
                    ['name'=>'John Isner', 'pic'=>'ji.jpg'],
                    ['name'=>'Michael Jordan', 'pic'=>'mj.jpg'],
                    ['name'=>'Novak Djokovic', 'pic'=>'nd.png'],
                    ['name'=>'Roger Federer', 'pic'=>'rf.jpg'],
                    ['name'=>'Venice Williams', 'pic'=>'vw.png']
                ];
                for($i = 0; $i < 12; $i++):
                ?>
                    <?php
                        $rand = rand(0, count($pics) - 1);
                    ?>
                    <div class="col-sm-6 friendly" style="text-align:center">
                        <img src="/~wangchj/countup/web/images/friends/<?=$pics[$rand]['pic']?>" style="width:50px;" class="img-circle img-thumbnail">
                        <div class="friendly-name"><?=$pics[$rand]['name']?> <!-- span class="badge" style="font-size:8px;"><?=rand(1, 100)?></span--></div>
                    </div>
                    <?php array_splice($pics, $rand, 1);?>
                <?php endfor;?>
            </div>
        </div>
    </div>
<?php endif;?>
</div>

<script>
var data = <?=json_encode($data)?>;
</script>
