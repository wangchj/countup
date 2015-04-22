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

<?php echo $this->render('@app/views/layouts/header-big.php', ['viewer'=>$viewer, 'viewee'=>$viewee]);?>

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
                    <div class="col-xs-6" style="padding-top:15px; padding-bottom:15px"> <!-- Produce gutters between counters -->
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
                                    <div style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px">Current Count</div>
                                    <div style="margin-bottom:8px"><?=$counter->getDays()?> Days</div>
                                    <div style="font-size:12px; font-weight:normal; color:#ccc">
                                         <?= $counter->isActive() ? 'since ' . $counter->getCurrentStartDate()->format('F j, Y') : 'not running'?>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div style="font-size:12px; font-weight:normal; color:#999; margin-bottom:5px">Best</div>
                                    <?=$counter->getBest()?> Days
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
            
        </div>
    </div>
<?php endif;?>

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
