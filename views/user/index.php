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

.counter-menu {
    padding-left:0px;
    padding-right:0px;
    margin:0px;
}

.counter-menu-item a {
    display: block;
    padding: 5px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.5;
    color: #333;
    white-space: nowrap;
    list-style-type: none;
    list-style: none;
    text-decoration: none;
    text-shadow: 0px 1px 1px #fff;
}

/*.counter-menu-item a:hover{
    color:#333;
    text-decoration: none;
}*/

.counter-menu-item {
    list-style-type: none;"
    margin:0px;
}

.counter-menu-item:hover{
    background-color: #eee;
}

.popover-content {
    padding-left:0px;
    padding-right:0px;
}

a.counter-setting, a.counter-setting:hover, a.counter-setting:active {
    font-size:14px;
    color:#999;
    text-shadow: 0px 1px 1px #fff;
    text-decoration: none;
}

.date-menu {
    padding-left:0px;
    padding-right:0px;
    margin:0px;
}

.date-menu-item {
    display: block;
    padding: 5px 20px;
    clear: both;
    font-weight: normal;
    font-size:12px;
    line-height: 1.5;
    color: #333;
    white-space: nowrap;
    list-style-type: none;
    list-style: none;
    text-decoration: none;
    text-shadow: 0px 1px 1px #fff;
}

.date-menu-item:hover {
    background-color: #eee;
}

.date-menu-item a{
    color: #333;
    text-decoration:none;
}


.modal .box-select {
    padding:0px;
    display:inline-block;
    list-style:none;
    /*margin-left:5px;*/
    /*border-radius:3px;*/

}

.modal .box-select li {
    display:inline;
    /*position: relative;
    float: left;*/
    /*font-size:14px;*/
    padding: 5px 12px;
    margin-left: -5px;
    line-height: 1.42857143;
    /*color: #357cb9;*/
    /*text-decoration: none;*/
    background-color: #fff;
    border: 1px solid #ddd;
}

.modal .box-select>li:first-child {
    margin-left: 0;
    border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
}

.modal .box-select>li:last-child {
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
}

.modal .box-select>li.active {
    z-index: 2;
    color: #fff;
    cursor: default;
    background-color: #337ab7;
    border-color: #337ab7;
}

.modal .box-select>li>a {
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
}

.modal .box-select>li.active>a {
    color: #fff;
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
                    <div id="counter-container-<?=$counter->counterId?>" class="col-sm-6" style="padding-top:15px; padding-bottom:15px"> <!-- Produce gutters between counters -->
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
                                            <a class="counter-setting" href="#" counterId="<?=$counter->counterId?>">
                                                <span class="glyphicon glyphicon-cog"></span>
                                            </a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px">Current Count</div>
                                    <div style="margin-bottom:8px"><span class="current-count"><?=$counter->getDays()?></span> Days</div>
                                    <div style="font-size:12px; font-weight:normal; color:#ccc">
                                         <?= $counter->isActive() && $counter->getCurrentStartDate() ? 'since ' . $counter->getCurrentStartDate()->format('M j, Y') : 'not running'?>
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
                                            <svg id="<?=$counter->counterId?>"></svg>
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
var markUrl = '<?=Url::to(['counter/mark'])?>';
var getDaysUrl = '<?=Url::to(['counter/get-days'])?>';
var counterRemoveUrl = '<?=Url::to(['counter/ajax-remove'])?>';
var counterAddUrl = '<?=Url::to(['counter/add'])?>';
</script>

<style>
.model-title {

}
</style>

<div id="add-counter-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>New Counter</b></h4>
            </div>
            <div class="modal-body">
                <form id="counter-form">
                    <div class="form-group field-counter-label required">
                        <label class="control-label" for="counter-label">Label</label>
                        <input type="text" id="counter-label" class="form-control" name="Counter[label]" maxlength="30">
                    </div>

                    <div class="form-group field-counter-summary">
                        <label class="control-label" for="counter-summary">Summary</label>
                        <textarea id="counter-summary" class="form-control" name="Counter[summary]"></textarea>
                    </div>

                    <div class="form-group">
                        <a href="#new-counter-more" id="new-counter-more-link" data-toggle="collapse" aria-expanded="false" aria-controls="new-counter-more">More options</a>
                    </div>

                    <div id="new-counter-more" class="collapse">

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group field-counter-type required">
                                        <input type="hidden" id="counter-type" name="Counter[type]" value="daily">
                                        <!-- label class="control-label" for="counter-type" style="margin-right:10px">Type</label -->
                                        <ul class="box-select" style="margin-right:20px">
                                            <li id="type-select-item-daily" class="type-select-item active"><a href="#">Daily</a></li>
                                            <li id="type-select-item-weekly" class="type-select-item"><a href="#">Weekly</a></li>
                                        </ul>

                                        <div id="counter-option-daily" style="display:inline">
                                            every
                                            <input type="text" id="counter-every" name="Counter[every]" value="1" class="form-control"
                                                style="display:inline;width:35px; padding:2px 6px; height:28px; margin-left:15px; margin-right:15px;text-align:center"
                                                maxlength="1">
                                            <span id="every-label">day</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="weekly-day-select" style="display:none">
                                <div class="col-xs-12">
                                    <div class="form-group field-counter-type">
                                        <label class="control-label">On</label>
                                        <div>
                                        <ul class="box-select" style="margin-right:20px">
                                            <li><a href="#">Mon</a></li>
                                            <li><a href="#">Tue</a></li>
                                            <li><a href="#">Wed</a></li>
                                            <li><a href="#">Thu</a></li>
                                            <li><a href="#">Fri</a></li>
                                            <li><a href="#">Sat</a></li>
                                            <li><a href="#">Sun</a></li>
                                        </ul>
                                        <input type="hidden" id="day-mon" name="day[mon]">
                                        <input type="hidden" id="day-tue" name="day[tue]">
                                        <input type="hidden" id="day-wed" name="day[wed]">
                                        <input type="hidden" id="day-thu" name="day[thu]">
                                        <input type="hidden" id="day-fri" name="day[fri]">
                                        <input type="hidden" id="day-sat" name="day[sat]">
                                        <input type="hidden" id="day-sun" name="day[sun]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group field-counter-startdate required">
                                        <label class="control-label" for="counter-startdate">Starting</label>
                                        <input type="text" id="counter-startdate" class="form-control" name="Counter[startDate]">
                                    </div>
                                </div>
                                <div class="col-xs-5">
                                    <div class="form-group field-counter-timezone required">
                                        <label class="control-label" for="counter-timezone">Time Zone</label>
                                        <select id="counter-timezone" class="form-control" name="Counter[timeZone]">
                                        <?php foreach(DateTimeZone::listIdentifiers() as $timezone):?>
                                            <option value="<?=$timezone?>"<?=Yii::$app->user->identity->timeZone == $timezone ? 'selected' : ''?>><?=$timezone?></option>
                                        <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group field-counter-startdate required">
                                        <!-- input type="hidden" name="Counter[public]" value="0" -->
                                        <label><input type="checkbox" id="counter-public" name="Counter[public]" value="1"> Public</label>
                                    </div>
                                </div>
                            </div>

                        </div><!-- container-fluid --> 
                    </div> <!-- collapse -->

                </form>
            </div>
            <div class="modal-footer">
                <!-- button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button-->
                <button type="button" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->