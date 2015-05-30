<?php

use yii\helpers\Url;
use yii\web\AssetBundle;
use app\lib\DateTime;

class Asset extends AssetBundle {
    public $sourcePath = '@app/views/user';
    public $js = ['cal.js', 'index.js', 'counter-sortable.js'];
    public $css = ['cal.css'];
    public $depends = [
        'app\assets\MarkDateModalAsset',
        'app\assets\SnapsvgAsset',
        'yii\web\JqueryAsset',
        'app\assets\SortableAsset',
    ];
}
Asset::register($this);

$this->params['viewer'] = $viewer;
$this->params['viewee'] = $viewee;
?>

<style>
.counter-menu {
    padding-left:0px;
    padding-right:0px;
    margin:0px;
}

.counter-menu-item {
    list-style-type: none;"
    margin:0px;
    padding: 5px 20px;
    color:#333;
    white-space: nowrap;
    text-shadow: 0px 1px 1px #fff;
    cursor: pointer;
}

.counter-menu-item:hover{
    background-color: #eee;
}

.popover-content {
    padding-left:0px;
    padding-right:0px;
}

.counter-menu-toggle, .counter-menu-toggle:focus {
    background-color: transparent;
    outline:0px;
    border:0px;
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
    cursor: pointer;
}

.date-menu-item:hover {
    background-color: #eee;
}

.date-menu-item a{
    color: #333;
    text-decoration:none;
}
</style>

<div class="row">
    <div class="col-md-10 col-sm-12"> <!-- Container for counters -->
        
        <?php if(count($counters) == 0):?>
            <?php echo $this->render('@app/views/layouts/NoCounterWidget.php', ['viewer'=>$viewer, 'viewee'=>$viewee])?>
        <?php endif;?>

        <div class="row counters-sortable">
            <?php foreach($counters as $counter):?>
                <div id="counter-container-<?=$counter->counterId?>" counterid="<?=$counter->counterId?>" class="col-sm-6" style="padding-top:15px; padding-bottom:15px"> <!-- Produce gutters between counters -->
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
                                        <!-- a class="counter-menu-toggle" href="#" counterId="<?=$counter->counterId?>" -->
                                        <button class="counter-menu-toggle" counterId="<?=$counter->counterId?>"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                                        <!-- /a -->
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
    <div class="col-sm-2 hidden-sm friends-side">
        <?php if($follows):?>
            <div class="row">
                <div class="col-sm-12">
                    <h2>Follows</h2>
                </div>
            </div>
            <div class="row">
                <?php foreach($follows as $follow): ?>
                    <div class="col-sm-6 friend" style="text-align:center">
                        <a href="<?=Url::to(['user/index', 'token'=>$follow->getIdentifier()])?>">
                            <img src="<?=$follow->getPicture()?>" style="width:50px;" class="img-circle img-thumbnail">
                            <div>
                                <?="{$follow->forename} {$follow->surname}"?>
                            </div>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endif;?>

        <?php if($followers):?>
            <div class="row">
                <div class="col-sm-12">
                    <h2>Followers</h2>
                </div>
            </div>
            <div class="row">
                <?php foreach($followers as $follower): ?>
                    <div class="col-sm-6 friend" style="text-align:center">
                        <a href="<?=Url::to(['user/index', 'token'=>$follower->getIdentifier()])?>">
                            <img src="<?=$follower->getPicture()?>" style="width:50px;" class="img-circle img-thumbnail">
                            <div>
                                <?="{$follower->forename} {$follower->surname}"?>
                            </div>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endif;?>
        
    </div>
</div>

<script>
var data = <?=json_encode($data)?>;
var markUrl = '<?=Url::to(['counter/mark'])?>';
var getDaysUrl = '<?=Url::to(['counter/get-days'])?>';
var counterRemoveUrl = '<?=Url::to(['counter/ajax-remove'])?>';
var counterAddUrl = '<?=Url::to(['counter/add'])?>';
var counterDataUrl = '<?=Url::to(['counter/data'])?>';
var counterUpdateUrl = '<?=Url::to(['counter/update'])?>';
var updateOrderUrl = '<?=Url::to(['counter/update-display-order'])?>';
var followUrl = '<?=Url::to(['user/follow'])?>';
var unfollowUrl = '<?=Url::to(['user/unfollow'])?>';
var fastForwardUrl = '<?=Url::to(['counter/fast-forward'])?>';

var app = {
    user: {
        userId: <?=Yii::$app->user->identity->userId?>
    }
};
</script>

<?=$this->render('@app/views/layouts/CounterFormModal.php');?>

<?=$this->render('@app/views/layouts/MarkDateModal.php');?>

<div id="remove-confirm-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Remove Counter</b></h4>
            </div>
            <div class="modal-body">
                All history of this counter will be removed. Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-no">No, I don't want this!</button>
                <button type="button" class="btn btn-danger btn-yes">Yes, I am sure</button>
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->